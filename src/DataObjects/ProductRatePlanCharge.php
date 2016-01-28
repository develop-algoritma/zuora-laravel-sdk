<?php

namespace Spira\ZuoraSdk\DataObjects;

use Spira\ZuoraSdk\DataObject;

/**
 * @see https://knowledgecenter.zuora.com/BC_Developers/SOAP_API/E1_SOAP_API_Object_Reference/ProductRatePlanCharge
 */
class ProductRatePlanCharge extends DataObject
{
    protected $type = 'ProductRatePlanCharge';

    const APPLY_DISCOUNT_TO_ONETIME = 'ONETIME';
    const APPLY_DISCOUNT_TO_RECURRING = 'RECURRING';
    const APPLY_DISCOUNT_TO_USAGE = 'USAGE';
    const APPLY_DISCOUNT_TO_ONETIMERECURRING = 'ONETIMERECURRING';
    const APPLY_DISCOUNT_TO_ONETIMEUSAGE = 'ONETIMEUSAGE';
    const APPLY_DISCOUNT_TO_RECURRINGUSAGE = 'RECURRINGUSAGE';
    const APPLY_DISCOUNT_TO_ONETIMERECURRINGUSAGE = 'ONETIMERECURRINGUSAGE';

    const BILL_CYCLE_TYPE_DEFAULT_FROM_CUSTOMER = 'DefaultFromCustomer';
    const BILL_CYCLE_TYPE_SPECIFIC_DAY_OF_MONTH = 'SpecificDayofMonth';
    const BILL_CYCLE_TYPE_SUBSCRIPTION_START_DAY = 'SubscriptionStartDay';
    const BILL_CYCLE_TYPE_CHARGE_TRIGGER_DAY = 'ChargeTriggerDay';
    const BILL_CYCLE_TYPE_SPECIFIC_DAY_OF_WEEK = 'SpecificDayofWeek';

    const BILLING_PERIOD_MONTH = 'Month';
    const BILLING_PERIOD_QUARTER = 'Quarter';
    const BILLING_PERIOD_ANNUAL = 'Annual';
    const BILLING_PERIOD_SEMI_ANNUAL = 'Semi-Annual';
    const BILLING_PERIOD_SPECIFIC_MONTHS = 'Specific Months';
    const BILLING_PERIOD_SUBSCRIPTION_TERM = 'Subscription Term';
    const BILLING_PERIOD_WEEK = 'Week';
    const BILLING_PERIOD_SPECIFIC_WEEKS = 'Specific Weeks';

    const BILLING_PERIOD_ALIGNMENT_ALIGN_TO_CHARGE = 'AlignToCharge';
    const BILLING_PERIOD_ALIGNMENT_ALIGN_TO_SUBSCRIPTION_START = 'AlignToSubscriptionStart';
    const BILLING_PERIOD_ALIGNMENT_ALIGN_TO_TERM_START = 'AlignToTermStart';

    const BILLING_TIMING_IN_ADVANCE = 'InAdvance';
    const BILLING_TIMING_IN_ARREARS = 'InArrears';

    const CHARGE_MODEL_DISCOUNT_FIXED_AMOUNT = 'Discount-Fixed Amount';
    const CHARGE_MODEL_DISCOUNT_PERCENTAGE = 'Discount-Percentage';
    const CHARGE_MODEL_FLAT_FEE_PRICING = 'Flat Fee Pricing';
    const CHARGE_MODEL_PER_UNIT_PRICING = 'Per Unit Pricing';
    const CHARGE_MODEL_OVERAGE_PRICING = 'Overage Pricing';
    const CHARGE_MODEL_TIERED_PRICING = 'Tiered Pricing';
    const CHARGE_MODEL_TIERED_WITH_OVERAGE_PRICING = 'Tiered with Overage Pricing';
    const CHARGE_MODEL_VOLUME_PRICING = 'Volume Pricing';

    const CHARGE_TYPE_ONETIME = 'OneTime';
    const CHARGE_TYPE_RECURRING = 'Recurring';
    const CHARGE_TYPE_USAGE = 'Usage';

    const DISCOUNT_LEVEL_RATEPLAN = 'rateplan';
    const DISCOUNT_LEVEL_SUBSCRIPTION = 'subscription';
    const DISCOUNT_LEVEL_ACCOUNT = 'account';

    const LIST_PRICE_BASE_PER_MONTH = 'Per_Month';
    const LIST_PRICE_BASE_PER_BILLING_PERIOD = 'Per_Billing Period';
    const LIST_PRICE_BASE_PER_WEEK = 'Per_Week';

    const OVERAGE_CALCULATION_OPTION_EndOfSmoothingPeriod = 'EndOfSmoothingPeriod';
    const OVERAGE_CALCULATION_OPTION_PerBillingPeriod = 'PerBillingPeriod';

    const OVERAGE_UNUSED_UNITS_CREDIT_OPTION_NoCredit = 'NoCredit';
    const OVERAGE_UNUSED_UNITS_CREDIT_OPTION_CreditBySpecificRate = 'CreditBySpecificRate';

    const PRICE_CHANGE_OPTION_NO_CHANGE = 'NoChange';
    const PRICE_CHANGE_OPTION_SPECIFIC_PERCENTAGE_VALUE = 'SpecificPercentageValue';
    const PRICE_CHANGE_OPTION_USE_LATEST_PRODUCT_CATALOG_PRICING = 'UseLatestProductCatalogPricing';

    const PRICE_INCREASE_OPTION_FROM_TENANT_PERCENTAGE_VALUE = 'FromTenantPercentageValue';
    const PRICE_INCREASE_OPTION_SPECIFIC_PERCENTAGE_VALUE = 'SpecificPercentageValue';

    const RATING_GROUP_BY_BILLING_PERIOD = 'ByBillingPeriod';
    const RATING_GROUP_BY_USAGE_START_DATE = 'ByUsageStartDate';
    const RATING_GROUP_BY_USAGE_RECORD = 'ByUsageRecord';
    const RATING_GROUP_BY_USAGE_UPLOAD = 'ByUsageUpload';

    const REVENUE_RECOGNITION_RULE_NAME_RECOGNIZE_UPON_INVOICING = 'Recognize upon invoicing';
    const REVENUE_RECOGNITION_RULE_NAME_RECOGNIZE_DAILY_OVER_TIME = 'Recognize daily over time';

    const REV_REC_TRIGGER_CONDITION_CONTRACT_EFFECTIVE_DATE = 'ContractEffectiveDate';
    const REV_REC_TRIGGER_CONDITION_SERVICE_ACTIVATION_DATE = 'ServiceActivationDate';
    const REV_REC_TRIGGER_CONDITION_CUSTOMER_ACCEPTANCE_DATE = 'CustomerAcceptanceDate';

    const SMOOTHING_MODEL_ROLLING_WINDOW = 'RollingWindow';
    const SMOOTHING_MODEL_ROLLOVER = 'Rollover';

    const TAX_MODE_TAX_EXCLUSIVE = 'TaxExclusive';
    const TAX_MODE_TAX_INCLUSIVE = 'TaxInclusive';

    const TRIGGER_EVENT_CONTRACT_EFFECTIVE = 'ContractEffective';
    const TRIGGER_EVENT_SERVICE_ACTIVATION = 'ServiceActivation';
    const TRIGGER_EVENT_CUSTOMER_ACCEPTANCE = 'CustomerAcceptance';

    const UP_TO_PERIODS_TYPE_BILLING_PERIODS = 'Billing Periods';
    const UP_TO_PERIODS_TYPE_DAYS = 'Days';
    const UP_TO_PERIODS_TYPE_WEEKS = 'Weeks';
    const UP_TO_PERIODS_TYPE_MONTHS = 'Months';
    const UP_TO_PERIODS_TYPE_YEARS = 'Years';

    const USAGE_RECORD_RATING_OPTION_END_OF_BILLING_PERIOD = 'EndOfBillingPeriod (default)';
    const USAGE_RECORD_RATING_OPTION_ON_DEMAND = 'OnDemand';

    const WEEKLY_BILL_CYCLE_DAY_SUNDAY = 'Sunday';
    const WEEKLY_BILL_CYCLE_DAY_MONDAY = 'Monday';
    const WEEKLY_BILL_CYCLE_DAY_TUESDAY = 'Tuesday';
    const WEEKLY_BILL_CYCLE_DAY_WEDNESDAY = 'Wednesday';
    const WEEKLY_BILL_CYCLE_DAY_THURSDAY = 'Thursday';
    const WEEKLY_BILL_CYCLE_DAY_FRIDAY = 'Friday';
    const WEEKLY_BILL_CYCLE_DAY_SATURDAY = 'Saturday';

    public static function getDefaultColumns()
    {
        return [
            'Id',
            'Name',
            'AccountingCode',
            'ApplyDiscountTo',
            'BillCycleDay',
            'BillCycleType',
            'BillingPeriod',
            'BillingPeriodAlignment',
            'BillingTiming',
            'ChargeModel',
            'ChargeType',
            'CreatedById',
            'CreatedDate',
            'DefaultQuantity',
            'DeferredRevenueAccount',
            'Description',
            'DiscountLevel',
            'EndDateCondition',
            'IncludedUnits',
            'ListPriceBase',
            'MaxQuantity',
            'MinQuantity',
            'NumberOfPeriod',
            'OverageCalculationOption',
            'OverageUnusedUnitsCreditOption',
            'PriceChangeOption',
            // 'PriceIncreaseOption', - Api fails when trying to retrieve
            'PriceIncreasePercentage',
            'ProductRatePlanId',
            'RatingGroup',
            'RecognizedRevenueAccount',
            'RevenueRecognitionRuleName',
            'RevRecCode',
            'RevRecTriggerCondition',
            'SmoothingModel',
            'SpecificBillingPeriod',
            'Taxable',
            'TaxCode',
            'TaxMode',
            'TriggerEvent',
            'UOM',
            'UpdatedById',
            'UpdatedDate',
            'UpToPeriods',
            'UpToPeriodsType',
            'UsageRecordRatingOption',
            'UseDiscountSpecificAccountingCode',
            'UseTenantDefaultForPriceChange',
            'WeeklyBillCycleDay',
        ];
    }

    public static function getOptionalColumns()
    {
        return [
            'ApplyDiscountTo',
            'BillCycleDay',
            'BillCycleType',
            'BillingPeriod',
            'BillingPeriodAlignment',
            'BillingTiming',
            'DefaultQuantity',
            'Description',
            'DiscountLevel',
            'IncludedUnits',
            'MaxQuantity',
            'MinQuantity',
            'NumberOfPeriod',
            'RatingGroup',
            'RevRecCode',
            'RevRecTriggerCondition',
            'SmoothingModel',
            'SpecificBillingPeriod',
            'UOM',
            'UpToPeriods',
            'UsageRecordRatingOption',
            'UseDiscountSpecificAccountingCode',
            'WeeklyBillCycleDay',
        ];
    }
}
