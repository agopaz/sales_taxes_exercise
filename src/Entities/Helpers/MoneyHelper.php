<?php

namespace SalesTaxesExample\Entities\Helpers;

use Money\Money;
use Money\Currency;
use Money\Currencies\ISOCurrencies;
use Money\Parser\DecimalMoneyParser;
use Money\Formatter\DecimalMoneyFormatter;

/**
 * Helper for money.
 *
 * @author Agostino Pagnozzi
 */
class MoneyHelper
{
    protected static $_instance = null;


    /**
     * Statically declared default currency code.
     *
     * It can be replaced by a global application setting.
     *
     * @var string
     */
    protected static $_defaultCurrencyCode = 'EUR';


    /**
     * Instance of the class.
     *
     * @return TaxHelper
     */
    public static function getInstance(): MoneyHelper
    {
        if (null === static::$_instance) {
            static::$_instance = new static();
        }

        return static::$_instance;
    }


    /**
     * Protected constructor.
     */
    protected function __construct()
    {    }


    /**
     * Default store currency.
     *
     * @return Money
     */
    public function getDefaultCurrency(): Currency
    {
        return new Currency(static::$_defaultCurrencyCode);
    }


    /**
     * Parses string, integer or float into a money instance.
     *
     * @param string|int|float|Money $value
     * @param Currency|null $currency
     *
     * @return Money
     */
    public function parse($value, Currency $currency = null): Money
    {
        if ($value instanceof Money) {
            return $value;
        }

        // Money Parser:
        $currencies = new ISOCurrencies();
        $moneyParser = new DecimalMoneyParser($currencies);

        return $moneyParser->parse((string)$value, $currency ?? $this->getDefaultCurrency());
    }


    /**
     * Format as decimal a money instance.
     *
     * @param Money $value
     * @return string
     */
    public function asDecimal(Money $value): string
    {
        // Money formatter:
        $currencies = new ISOCurrencies();
        $moneyFormatter = new DecimalMoneyFormatter($currencies);

        return $moneyFormatter->format($value);
    }


    private function __clone()
    {     }

    private function __wakeup()
    {    }
}