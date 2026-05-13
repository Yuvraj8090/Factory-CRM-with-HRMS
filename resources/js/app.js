import './bootstrap';

import '@fortawesome/fontawesome-free/css/all.min.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'admin-lte/dist/css/adminlte.min.css';
import 'datatables.net-bs5/css/dataTables.bootstrap5.css';

import $ from 'jquery';
import Popper from 'popper.js';
import Alpine from 'alpinejs';
import DataTable from 'datatables.net-bs5';

window.$ = $;
window.jQuery = $;
window.Popper = Popper;
window.DataTable = DataTable;

await import('bootstrap');
await import('admin-lte');

window.Alpine = Alpine;

Alpine.start();

DataTable(window, $);

const APP_STORAGE_PREFIX = 'factory-crm';

const storageHelpers = {
    buildKey(key) {
        return `${APP_STORAGE_PREFIX}.${key}`;
    },
    handleError(action, key, error) {
        console.warn(`localStorage ${action} failed for ${key}`, error);
        window.dispatchEvent(new CustomEvent('app-local-storage-error', {
            detail: { action, key, message: error?.message ?? 'Unknown localStorage error' },
        }));
        return null;
    },
    save(key, value) {
        try {
            localStorage.setItem(this.buildKey(key), JSON.stringify(value));
            return true;
        } catch (error) {
            return this.handleError('save', key, error);
        }
    },
    get(key, fallback = null) {
        try {
            const value = localStorage.getItem(this.buildKey(key));
            return value ? JSON.parse(value) : fallback;
        } catch (error) {
            this.handleError('read', key, error);
            return fallback;
        }
    },
    update(key, nextValue) {
        const current = this.get(key, {});
        const value = typeof nextValue === 'function' ? nextValue(current) : { ...current, ...nextValue };
        this.save(key, value);
        return value;
    },
    remove(key) {
        try {
            localStorage.removeItem(this.buildKey(key));
            return true;
        } catch (error) {
            return this.handleError('remove', key, error);
        }
    },
    clearNamespace(prefix = '') {
        try {
            Object.keys(localStorage)
                .filter((key) => key.startsWith(this.buildKey(prefix)))
                .forEach((key) => localStorage.removeItem(key));

            return true;
        } catch (error) {
            return this.handleError('clear', prefix, error);
        }
    },
};

window.AppLocalStore = storageHelpers;

const persistFormState = (form) => {
    const storageKey = form.dataset.localStorageForm;
    if (!storageKey) {
        return;
    }

    const ignoredFields = new Set(['_token', '_method', 'password', 'password_confirmation']);
    const saved = storageHelpers.get(storageKey, {});

    Array.from(form.elements).forEach((field) => {
        if (!field.name || ignoredFields.has(field.name) || field.type === 'file') {
            return;
        }

        if (saved[field.name] === undefined) {
            return;
        }

        if (field.type === 'checkbox' || field.type === 'radio') {
            field.checked = saved[field.name] === field.value || saved[field.name] === true;
            return;
        }

        field.value = saved[field.name];
    });

    const saveCurrentState = () => {
        const payload = {};

        Array.from(form.elements).forEach((field) => {
            if (!field.name || ignoredFields.has(field.name) || field.type === 'file') {
                return;
            }

            if (field.type === 'checkbox') {
                payload[field.name] = field.checked;
                return;
            }

            if (field.type === 'radio') {
                if (field.checked) {
                    payload[field.name] = field.value;
                }
                return;
            }

            payload[field.name] = field.value;
        });

        storageHelpers.save(storageKey, payload);
    };

    form.addEventListener('input', saveCurrentState);
    form.addEventListener('change', saveCurrentState);

    if (form.dataset.localStorageClearOnSubmit !== 'false') {
        form.addEventListener('submit', () => storageHelpers.remove(storageKey));
    }
};

const initWorkflow = (container) => {
    const workflowKey = container.dataset.workflow;
    const stepPanels = Array.from(container.querySelectorAll('[data-workflow-step]'));
    const progressBar = container.querySelector('[data-workflow-progress]');
    const statusText = container.querySelector('[data-workflow-status]');
    const stepButtons = Array.from(container.querySelectorAll('[data-workflow-target]'));

    if (!workflowKey || stepPanels.length === 0) {
        return;
    }

    const optionalSteps = new Set(
        stepPanels
            .filter((panel) => panel.dataset.workflowOptional === 'true')
            .map((panel) => panel.dataset.workflowStep)
    );

    const savedState = storageHelpers.get(`workflow.${workflowKey}`, {});
    let activeStep = savedState.activeStep || stepPanels[0].dataset.workflowStep;
    const skippedSteps = new Set(savedState.skippedSteps || []);

    const syncWorkflow = () => {
        const index = Math.max(stepPanels.findIndex((panel) => panel.dataset.workflowStep === activeStep), 0);
        const progress = ((index + 1) / stepPanels.length) * 100;

        stepPanels.forEach((panel, panelIndex) => {
            panel.classList.toggle('d-none', panel.dataset.workflowStep !== activeStep);
            panel.dataset.workflowState = panelIndex < index ? 'completed' : panel.dataset.workflowStep === activeStep ? 'active' : skippedSteps.has(panel.dataset.workflowStep) ? 'skipped' : 'upcoming';
        });

        stepButtons.forEach((button, buttonIndex) => {
            const target = button.dataset.workflowTarget;
            const isActive = target === activeStep;
            const isSkipped = skippedSteps.has(target);
            button.classList.toggle('active', isActive);
            button.classList.toggle('btn-outline-secondary', !isActive);
            button.classList.toggle('btn-primary', isActive);
            button.querySelector('[data-step-state]')?.replaceChildren(document.createTextNode(
                isSkipped ? 'Skipped' : buttonIndex < index ? 'Completed' : isActive ? 'Current' : 'Next'
            ));
        });

        if (progressBar) {
            progressBar.style.width = `${progress}%`;
            progressBar.setAttribute('aria-valuenow', `${progress}`);
        }

        if (statusText) {
            statusText.textContent = `Step ${index + 1} of ${stepPanels.length}`;
        }

        storageHelpers.save(`workflow.${workflowKey}`, {
            activeStep,
            skippedSteps: Array.from(skippedSteps),
        });
    };

    container.addEventListener('click', (event) => {
        const target = event.target.closest('[data-workflow-target],[data-workflow-next],[data-workflow-prev],[data-workflow-skip]');

        if (!target) {
            return;
        }

        if (target.dataset.workflowTarget) {
            activeStep = target.dataset.workflowTarget;
            syncWorkflow();
            return;
        }

        const currentIndex = Math.max(stepPanels.findIndex((panel) => panel.dataset.workflowStep === activeStep), 0);

        if (target.hasAttribute('data-workflow-next') && currentIndex < stepPanels.length - 1) {
            activeStep = stepPanels[currentIndex + 1].dataset.workflowStep;
        }

        if (target.hasAttribute('data-workflow-prev') && currentIndex > 0) {
            activeStep = stepPanels[currentIndex - 1].dataset.workflowStep;
        }

        if (target.hasAttribute('data-workflow-skip')) {
            const currentStep = stepPanels[currentIndex].dataset.workflowStep;
            if (optionalSteps.has(currentStep)) {
                skippedSteps.add(currentStep);
            }

            if (currentIndex < stepPanels.length - 1) {
                activeStep = stepPanels[currentIndex + 1].dataset.workflowStep;
            }
        }

        syncWorkflow();
    });

    syncWorkflow();
};

const buildAjaxParameters = (data, form) => {
    const payload = { ...data };

    if (!form) {
        return payload;
    }

    const formData = new FormData(form);
    for (const [key, value] of formData.entries()) {
        payload[key] = value;
    }

    return payload;
};

const initDataTable = (table) => {
    if (!table.dataset.datatableUrl || table.dataset.datatableInitialized === 'true') {
        return;
    }

    const columns = JSON.parse(table.dataset.datatableColumns || '[]');
    const filterSelector = table.dataset.datatableFilterForm;
    const filterForm = filterSelector ? document.querySelector(filterSelector) : null;
    const wrapper = table.closest('.app-card');
    const paginationWrapper = wrapper?.querySelector('[data-pagination-wrapper]');
    const storageKey = table.dataset.datatableStorageKey;
    const persistedFilters = storageKey ? storageHelpers.get(`datatable.filters.${storageKey}`, {}) : {};

    if (filterForm) {
        Object.entries(persistedFilters || {}).forEach(([key, value]) => {
            const field = filterForm.elements.namedItem(key);
            if (field && typeof field.value !== 'undefined' && value !== null) {
                field.value = value;
            }
        });
    }

    const instance = $(table).DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        searching: true,
        order: [],
        ajax: {
            url: table.dataset.datatableUrl,
            data: (data) => buildAjaxParameters(data, filterForm),
        },
        columns,
        drawCallback: () => {
            if (paginationWrapper) {
                paginationWrapper.classList.add('d-none');
            }
            Array.from(document.querySelectorAll('[data-bs-toggle="tooltip"]')).forEach((tooltipEl) => {
                if (!bootstrap.Tooltip.getInstance(tooltipEl)) {
                    new bootstrap.Tooltip(tooltipEl);
                }
            });
        },
        language: {
            emptyTable: 'No records matched the current filters.',
            zeroRecords: 'No matching records found.',
        },
    });

    if (filterForm) {
        filterForm.addEventListener('submit', (event) => {
            event.preventDefault();

            if (storageKey) {
                const currentFilters = Object.fromEntries(new FormData(filterForm).entries());
                storageHelpers.save(`datatable.filters.${storageKey}`, currentFilters);
            }

            instance.ajax.reload();
        });
    }

    table.dataset.datatableInitialized = 'true';
    window.dispatchEvent(new CustomEvent('app-datatable-ready', { detail: { id: table.id } }));
};

const initInlineStatusDropdowns = () => {
    document.addEventListener('click', async (event) => {
        const option = event.target.closest('[data-status-option]');

        if (!option) {
            return;
        }

        const dropdown = option.closest('[data-status-dropdown]');
        const updateUrl = dropdown?.dataset.updateUrl;
        const table = option.closest('table');

        if (!updateUrl) {
            return;
        }

        option.disabled = true;

        try {
            const response = await window.axios.patch(updateUrl, {
                activity_status_id: option.dataset.statusId,
            });

            window.dispatchEvent(new CustomEvent('app-status-updated', { detail: response.data }));
            $(table).DataTable().ajax.reload(null, false);
        } catch (error) {
            console.warn('Status update failed', error);
            window.dispatchEvent(new CustomEvent('app-local-storage-error', {
                detail: {
                    action: 'status-update',
                    key: dropdown?.dataset.recordLabel ?? 'activity',
                    message: error?.response?.data?.message ?? 'Status update failed.',
                },
            }));
        } finally {
            option.disabled = false;
        }
    });
};

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('form[data-local-storage-form]').forEach(persistFormState);
    document.querySelectorAll('[data-workflow]').forEach(initWorkflow);
    document.querySelectorAll('.app-data-table').forEach(initDataTable);
    initInlineStatusDropdowns();

    storageHelpers.update('session', {
        lastVisitedUrl: window.location.pathname,
        lastVisitedAt: new Date().toISOString(),
    });
});
