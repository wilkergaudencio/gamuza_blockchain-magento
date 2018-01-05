<?php
/**
 * @package     Gamuza_Blockchain
 * @description Bitcoin Crypto Currency Wallet
 * @copyright   Copyright (c) 2018 Gamuza Technologies (http://www.gamuza.com.br/)
 * @author      Eneias Ramos de Melo <eneias@gamuza.com.br>
 *
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Library General Public
 * License as published by the Free Software Foundation; either
 * version 2 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Library General Public License for more details.
 *
 * You should have received a copy of the GNU Library General Public
 * License along with this library; if not, write to the
 * Free Software Foundation, Inc., 51 Franklin St, Fifth Floor,
 * Boston, MA 02110-1301, USA.
 */

/**
 * See the AUTHORS file for a list of people on the Gamuza Team.
 * See the ChangeLog files for a list of changes.
 * These files are distributed with gamuza_blockchain-magento at http://github.com/gamuzatech/.
 */

/**
 * Currency model
 */
class Gamuza_Blockchain_Model_Directory_Currency extends Mage_Directory_Model_Currency
{
    protected $_paymentTypes = array ();

    public function _construct ()
    {
        parent::_construct ();

        $this->_paymentTypes = Mage::helper ('blockchain')->getConfig ()->getCcTypes ();
    }

    public function format ($price, $options = array (), $includeContainer = true, $addBrackets = false)
    {
        $precision = 2; // fiduciary

        if (in_array ($this->getCode (), array_keys ($this->_paymentTypes)))
        {
            $precision = intval ($this->_paymentTypes [$this->getCode ()]['precision']);
        }

        return $this->formatPrecision ($price, $precision, $options, $includeContainer, $addBrackets);
    }

    public function formatTxt ($price, $options = array ())
    {
        if (!is_numeric ($price))
        {
            $price = Mage::app ()->getLocale ()->getNumber ($price);
        }

        /**
         * Fix problem with 12 000 000, 1 200 000
         *
         * %f - the argument is treated as a float, and presented as a floating-point number (locale aware).
         * %F - the argument is treated as a float, and presented as a floating-point number (non-locale aware).
         */
        if (!in_array ($this->getCode (), array_keys ($this->_paymentTypes)))
        {
            $price = sprintf ("%F", $price);
        }

        if ($price == -0)
        {
            $price = 0;
        }

        return Mage::app ()->getLocale ()->currency ($this->getCode ())->toCurrency ($price, $options);
    }
}

