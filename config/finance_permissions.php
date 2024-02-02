<?php


/**
 * Permissions config
 *
 * @author Muhammad Adnan <adnannadeem1994@gmail.com>
 * @date   23/10/2020
 */

return [
    'Roles'=>
        [
            'List' => 'role.index',
            'Create' => 'role.create|role.store',
            'Edit' => 'role.edit|role.update',
            'View' => 'role.show',
            'Set permissions' => 'role.set-permissions|role.set-permissions.update',
        ],
    'Sub Admin'=>
        [
            'List' => 'sub-admin.index|sub-admin.data',
            'Create' => 'sub-admin.create|sub-admin.store',
            'Edit' => 'sub-admin.edit|sub-admin.update',
            'Status change' => 'sub-admin.active|sub-admin.inactive',
            'View' => 'sub-admin.show',
            'Delete' => 'sub-admin.destroy',
        ],

    'System Parameters'=>
        [
            'List' => 'system-parameters.index|system-parameters.data',
            //'Create' => 'system-parameters.create|system-parameters.store',
            'Edit' => 'system-parameters.edit|system-parameters.update',
            'View' => 'system-parameters.show',
            //'Delete' => 'system-parameters.destroy',
        ],
    'Joey Reports'=>
        [
            'Joey Route payout' => 'joey.reports.payout.index|joey.reports.payout.data',
        ],
    'Joey Plans'=>
        [
            'List' => 'joey-plan.index|joey.plan.data',
            'Create' => 'joey-plan.create|joey-plan.store|get-selected-hub-zones|save-or-update-zone-group',
            'Edit' => 'joey-plan.edit|joey-plan.update|joey.plan.detail.delete|get-selected-hub-zones|save-or-update-zone-group',
            'View' => 'joey-plan.show',
            'Set Plan Base Amount' => 'joey-plan.plan-base-amount.edit|joey-plan.plan-base-amount.update',
            'Set Plan To Joeys' => 'plan-assign-to-joeys.view|plan-assign-to-joyes.update|remove-joey-from-plan',
            'Manage Group By Zone' => 'joey-plan.manage.group-by-zone|get-selected-hub-zones|save-or-update-zone-group',
        ],
    'Assign Plan To Joeys'=>
        [
            'List' => 'assign-plan-to-joeys.index|assign-plan-to-joeys.data',
            'Assign plan to joey' => 'assign-plan-to-joeys.edit|assign-plan-to-joeys.update',
        ],
    'Merchant Plans'=>
        [
            'List' => 'merchant-plans.index|merchant-plans.data',
            'Create' => 'merchant-plans.create|merchant-plans.store',
            'Edit' => 'merchant-plans.edit|merchant-plans.update',
            'View' => 'merchant-plans.show',
            //'Delete' => 'merchant-plans.destroy',
        ],
    'Set Vendors City'=>
        [
            'List' => 'set-vendors-city.index|set-vendors-city.data',
            'Create' => 'set-vendors-city.create|set-vendors-city.store',
            'Edit' => 'set-vendors-city.edit|set-vendors-city.update|remove-vendor-from-city',
            'View' => 'set-vendors-city.show',
            'Delete' => 'set-vendors-city.destroy',
        ],
    'Invoices'=>
        [
            'List' => 'ctc.invoices.index|ctc.invoices.data|ctc.invoice-generate.csv',
            'Edit' => 'ctc.invoices.column.update',
        ],

    'Reporting'=>
        [
            'CTC Tracking Report' => 'ctc.tracking-report-generate.index|ctc.tracking-report-generate.csv',
            'CTC Narvar Tracking Report' => 'ctc-narvar.tracking-report.index|ctc-narvar.tracking-report-generate.csv',
        ],
    'CTC Brand'=>
        [
            'List' => 'ctc-brand.index|ctc-brand.data',
            'Add' => 'ctc-brand.create|ctc-brand.store',
            'Edit' => 'ctc-brand.edit|ctc-brand.update|remove-vendor-from-ctc-brand',
            'Delete' => 'ctc-brand.destroy'
        ],
    'Labels & Taxes'=>
        [
            'List' => 'taxes.index',
            //'Add Texes & Labels' => 'taxes.create|taxes.store',
            'Edit' => 'taxes.edit|taxes.update',
            'View' => 'taxes.show',
            //'CTC Brand Delete' => 'ctc-brand.destroy'
        ],
    'Brokers'=>
        [
            'List' => 'brokers.index|brooker.data',
            'Add' => 'brokers.create|brokers.store',
            'Edit' => 'brokers.edit|brokers.update',
            'Assign Joeys' => 'brooker-assign-to-joeys.view|brooker-assign-to-joeys|brooker-un-assign-to-joeys',
            'Delete' => 'brokers.destroy'
        ],
    'Flag Order List'=>
        [
            'List' => 'flag-order.index|flag-order.data',
            'View' => 'flag-order.show',
        ],
    'Economy Fuel rate'=>
        [
            'List' => 'economy-fuel-rate.index|economy-fuel-rate.data',
            'Add' => 'economy-fuel-rate.create|economy-fuel-rate.store'
        ],

];
