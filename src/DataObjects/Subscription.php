<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/DC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/Subscription
 */
class Subscription extends DataObject
{
    protected $type = 'Subscription';

    const TERM_PERIOD_TYPE_MONTH = 'Month';
    const TERM_PERIOD_TYPE_YEAR = 'Year';
    const TERM_PERIOD_TYPE_DAY = 'Day';
    const TERM_PERIOD_TYPE_WEEK = 'Week';

    const STATUS_DRAFT = 'Draft';
    const STATUS_PENDINGACTIVATION = 'PendingActivation';
    const STATUS_PENDINGACCEPTANCE = 'PendingAcceptance';
    const STATUS_ACTIVE = 'Active';
    const STATUS_CANCELLED = 'Cancelled';
    const STATUS_EXPIRED = 'Expired';

    const TERM_TYPE_TERMED = 'TERMED';
    const TERM_TYPE_EVERGREEN = 'EVERGREEN';

    public static function getDefaultColumns()
    {
        return [
            'AccountId',
            'AncestorAccountId',
            'AutoRenew',
            'CancelledDate',
            'ContractAcceptanceDate',
            'ContractEffectiveDate',
            'CreatedById',
            'CreatedDate',
            'CreatorAccountId',
            'CreatorInvoiceOwnerId',
            'CurrentTerm',
            'CurrentTermPeriodType',
            'Id',
            'InitialTerm',
            'InitialTermPeriodType',
            'InvoiceOwnerId',
            'IsInvoiceSeparate',
            'Name',
            'Notes',
            'OpportunityCloseDate__QT',
            'OpportunityName__QT',
            'OriginalCreatedDate',
            'OriginalId',
            'PreviousSubscriptionId',
            'QuoteBusinessType__QT',
            'QuoteNumber__QT',
            'QuoteType__QT',
            'RenewalSetting',
            'RenewalTerm',
            'RenewalTermPeriodType',
            'ServiceActivationDate',
            'Status',
            'SubscriptionEndDate',
            'SubscriptionStartDate',
            'TermEndDate',
            'TermStartDate',
            'TermType',
            'UpdatedById',
            'UpdatedDate',
            'Version',
        ];
    }
}
