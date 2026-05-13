# Factory CRM with HRMS

Factory CRM with HRMS is a Laravel 12 application that combines sales, finance, and HR workflows in one server-rendered web app. The project includes CRM records such as leads, activities, customers, quotations, invoices, and payments, alongside HRMS modules for employees, attendance, leave, and payroll.

This version adds:

- server-side Yajra DataTables on every index/listing screen
- an activity workflow with Step 1 → Step 2 → Step 3 guidance
- localStorage-backed workflow, filter, and draft persistence
- inline activity status changes with AJAX and audit logging

## Project Overview

The application is organized around four workspaces:

- `CRM`: leads, activities, customers, sales teams
- `Finance`: quotations, invoices, payments, debit notes
- `HRMS`: employees, attendance, departments, designations, leave, payroll
- `Settings`: categories, item masters, WhatsApp templates

The main operational table for workflow/status management is the `Activities` screen. Users can create activities through a guided 3-step form, optionally skip schedule details, and later update status directly from the index table without leaving the page.

## Installation

### 1. Clone and install dependencies

```bash
git clone <repository-url>
cd Factory-CRM-with-HRMS
composer install
npm install
```

### 2. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Update `.env` with your database, mail, and app settings.

### 3. Database setup

```bash
php artisan migrate
php artisan db:seed
```

If you prefer a clean local setup from scratch:

```bash
php artisan migrate:fresh --seed
```

### 4. Start the application

For local development:

```bash
composer run dev
```

For a production asset build:

```bash
npm run build
```

## Database Migrations

Important tables include:

- `leads`
- `activities`
- `activity_statuses`
- `activity_status_logs`
- `customers`
- `quotations`
- `invoices`
- `payments`
- `employees`
- `attendances`
- `leave_requests`
- `payroll_periods`

The `activity_status_logs` table stores every inline status change with:

- `activity_id`
- `from_status_id`
- `to_status_id`
- `changed_by`
- `changed_at`

## Feature Guide

### 1. Guided workflow and navigation

The activity create/edit form uses a 3-step structure:

1. `Step 1: Choose Lead`
2. `Step 2: Add Schedule`
3. `Step 3: Review & Save`

Workflow behavior:

- every step has a labeled action button
- the progress bar shows current step position
- Step 2 is optional and includes a `Skip This Optional Step` button
- helper copy explains what each button does
- activity draft data is preserved in localStorage until submit

### 2. localStorage implementation

Shared browser persistence is available on every page through `window.AppLocalStore`.

Supported operations:

- `save(key, value)`
- `get(key, fallback)`
- `update(key, valueOrCallback)`
- `remove(key)`
- `clearNamespace(prefix)`

Current usage:

- activity form draft persistence
- activity workflow step persistence
- DataTable filter persistence
- lightweight session/last-visited page sync

Error handling:

- localStorage calls are wrapped in `try/catch`
- failures emit an `app-local-storage-error` browser event
- the layout shows a warning message if persistence fails

### 3. Yajra DataTables

All listing/index views now use Yajra DataTables in server-side mode. The first page is still rendered by Blade so the screen remains usable before JavaScript enhancement finishes.

Server-side DataTables are enabled for:

- leads
- activities
- customers
- sales teams
- quotations
- invoices
- payments
- debit notes
- employees
- attendance
- departments
- designations
- leave types
- leave requests
- payrolls
- categories
- item masters
- WhatsApp templates

DataTable behavior:

- sorting is handled on the server
- searching is handled on the server
- pagination is handled on the server
- filters are sent with the DataTables AJAX request
- table refresh happens automatically after inline activity status updates

### 4. Status management

The `Activities` index includes a dedicated inline status dropdown.

How it works:

- clicking the status badge opens a dropdown menu
- choosing a new status sends an AJAX `PATCH` request
- the row refreshes through DataTables without a full page reload
- the status change is recorded in `activity_status_logs`
- the UI shows the latest status-change timestamp

Default activity statuses are seeded by `ActivityDataSeeder`:

- `Pending`
- `In Progress`
- `Completed`
- `Cancelled`
- `Rescheduled`

### 5. Index route/controller configuration

Each resource keeps its standard Laravel `index` action, but now supports two modes:

- normal request: returns the Blade view and initial server-rendered records
- DataTables request: returns Yajra JSON for server-side rendering

The main status-aware index is:

- `GET /activities` mapped to `ActivityController@index`

Inline status update endpoint:

- `PATCH /activities/{activity}/status` mapped to `ActivityController@updateStatus`

## API and Route Reference

### Workflow and status endpoints

- `GET /activities`: activities list view and DataTable JSON source
- `PATCH /activities/{activity}/status`: inline activity status update

### Core CRUD resource endpoints

- `crm.leads.*`
- `crm.activities.*`
- `crm.customers.*`
- `crm.sales-teams.*`
- `finance.quotations.*`
- `finance.invoices.*`
- `finance.payments.*`
- `finance.debit-notes.*`
- `hrms.employees.*`
- `hrms.attendances.*`
- `hrms.departments.*`
- `hrms.designations.*`
- `hrms.leave-types.*`
- `hrms.leave-requests.*`
- `hrms.payrolls.*`
- `settings.categories.*`
- `settings.item-masters.*`
- `settings.whats-app-templates.*`

### Purpose of the main custom endpoints

- `crm.leads.import`: import leads from file
- `crm.leads.export`: export lead data
- `crm.leads.convert`: convert a lead into a customer
- `crm.activities.update-status`: change activity status inline
- `crm.customers.send-email`: log and send customer email
- `crm.customers.send-whatsapp`: queue WhatsApp message
- `hrms.attendances.import`: import attendance
- `hrms.attendances.export`: export attendance
- `hrms.payrolls.submit-review`: move payroll into review
- `hrms.payrolls.approve`: approve payroll

## Frontend Workflow Explanation

### Activity creation flow

1. User opens `Activities > Add Activity`.
2. Step 1 captures lead, owner, type, status, subject, and description.
3. Step 2 optionally captures due date and completion time.
4. User can skip Step 2 and continue directly to Step 3.
5. Step 3 explains the save action and submits to Laravel.
6. On success, Laravel redirects to the activity detail page.

### Activity status update flow

1. User opens the `Activities` index.
2. User clicks the status badge in the status column.
3. A dropdown shows available statuses.
4. Selecting a status sends an AJAX request to the server.
5. The server updates the record and inserts a log entry.
6. The DataTable refreshes the row without reloading the page.

### Filter persistence flow

1. User applies filters on a list page.
2. Filter values are stored in localStorage.
3. On refresh or revisit, the filter form is restored.
4. The DataTable reloads using the restored filter state.

## Verification Checklist

The implementation has been validated with:

- `php artisan test`
- `npm run build`
- `php artisan migrate --pretend`

What to manually verify in the browser:

- activity create flow from Step 1 to Step 3
- optional step skip behavior
- activity draft persistence after refresh
- filter persistence on list screens
- inline activity status changes
- DataTables sorting, searching, and pagination
- CRUD redirects after create/update/delete operations

## Troubleshooting

### DataTable loads but rows do not refresh

- confirm the route returns JSON when the request includes DataTables parameters such as `draw`, `start`, and `length`
- confirm `APP_URL` and authentication session configuration are correct
- check the browser console for AJAX or localStorage warnings

### localStorage data is not saved

- verify the browser is not in a restricted/private mode blocking storage
- confirm the page shows no `Saved data warning`
- clear keys starting with `factory-crm.` and retry

### Migrations fail

- verify database credentials in `.env`
- make sure all required base tables have been migrated before seeding
- run `php artisan migrate:fresh --seed` in local development if the schema is out of sync

### Activity status change fails

- confirm the authenticated user can access the CRM routes
- verify `activity_statuses` has seeded data
- verify the `activity_status_logs` migration has run

### Frontend assets look outdated

- run `npm install`
- run `npm run build`
- clear Laravel caches with `php artisan optimize:clear`

## Testing

Run the test suite with:

```bash
php artisan test
```

Key coverage includes:

- customer HTML create flow
- payroll generation and approval flow
- activity inline status update logging
- activity DataTable server response

## Notes for Developers

- All DataTables use server-side Yajra responses from the existing controller `index` methods.
- The JavaScript entry point is `resources/js/app.js`.
- Shared local storage helpers are exposed through `window.AppLocalStore`.
- The activity workflow state is stored under the `factory-crm.workflow.*` namespace.
