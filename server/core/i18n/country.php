<?php
/*****************************************************************************
 *         In the name of God the Most Beneficent the Most Merciful          *
 *___________________________________________________________________________*
 *   This program is free software: you can redistribute it and/or modify    *
 *   it under the terms of the GNU General Public License as published by    *
 *   the Free Software Foundation, either version 3 of the License, or       *
 *   (at your option) any later version.                                     *
 *___________________________________________________________________________*
 *   This program is distributed in the hope that it will be useful,         *
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of          *
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the           *
 *   GNU General Public License for more details.                            *
 *___________________________________________________________________________*
 *   You should have received a copy of the GNU General Public License       *
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.   *
 *___________________________________________________________________________*
 *                             Created by  Qti3e                             *
 *        <http://Qti3e.Github.io>    LO-VE    <Qti3eQti3e@Gmail.com>        *
 *****************************************************************************/

namespace core\i18n;

use core\exception\youn_exception;

/**
 * Class country
 * @package core\i18n
 */
class country {
	/**
	 * List of ISO-3166
	 * Two letter code to Country name mapping.
	 * @var
	 */
	private static $countries   = array(
			'AF' => 'AFGHANISTAN',
			'AL' => 'ALBANIA',
			'DZ' => 'ALGERIA',
			'AS' => 'AMERICAN SAMOA',
			'AD' => 'ANDORRA',
			'AO' => 'ANGOLA',
			'AI' => 'ANGUILLA',
			'AQ' => 'ANTARCTICA',
			'AG' => 'ANTIGUA AND BARBUDA',
			'AR' => 'ARGENTINA',
			'AM' => 'ARMENIA',
			'AW' => 'ARUBA',
			'AU' => 'AUSTRALIA',
			'AT' => 'AUSTRIA',
			'AZ' => 'AZERBAIJAN',
			'BS' => 'BAHAMAS',
			'BH' => 'BAHRAIN',
			'BD' => 'BANGLADESH',
			'BB' => 'BARBADOS',
			'BY' => 'BELARUS',
			'BE' => 'BELGIUM',
			'BZ' => 'BELIZE',
			'BJ' => 'BENIN',
			'BM' => 'BERMUDA',
			'BT' => 'BHUTAN',
			'BO' => 'BOLIVIA',
			'BA' => 'BOSNIA AND HERZEGOWINA',
			'BW' => 'BOTSWANA',
			'BV' => 'BOUVET ISLAND',
			'BR' => 'BRAZIL',
			'IO' => 'BRITISH INDIAN OCEAN TERRITORY',
			'BN' => 'BRUNEI DARUSSALAM',
			'BG' => 'BULGARIA',
			'BF' => 'BURKINA FASO',
			'BI' => 'BURUNDI',
			'KH' => 'CAMBODIA',
			'CM' => 'CAMEROON',
			'CA' => 'CANADA',
			'CV' => 'CAPE VERDE',
			'KY' => 'CAYMAN ISLANDS',
			'CF' => 'CENTRAL AFRICAN REPUBLIC',
			'TD' => 'CHAD',
			'CL' => 'CHILE',
			'CN' => 'CHINA',
			'CX' => 'CHRISTMAS ISLAND',
			'CC' => 'COCOS (KEELING) ISLANDS',
			'CO' => 'COLOMBIA',
			'KM' => 'COMOROS',
			'CG' => 'CONGO',
			'CD' => 'CONGO, THE DEMOCRATIC REPUBLIC OF THE',
			'CK' => 'COOK ISLANDS',
			'CR' => 'COSTA RICA',
			'CI' => 'COTE D\'IVOIRE',
			'HR' => 'CROATIA (local name: Hrvatska)',
			'CU' => 'CUBA',
			'CY' => 'CYPRUS',
			'CZ' => 'CZECH REPUBLIC',
			'DK' => 'DENMARK',
			'DJ' => 'DJIBOUTI',
			'DM' => 'DOMINICA',
			'DO' => 'DOMINICAN REPUBLIC',
			'TP' => 'EAST TIMOR',
			'EC' => 'ECUADOR',
			'EG' => 'EGYPT',
			'SV' => 'EL SALVADOR',
			'GQ' => 'EQUATORIAL GUINEA',
			'ER' => 'ERITREA',
			'EE' => 'ESTONIA',
			'ET' => 'ETHIOPIA',
			'FK' => 'FALKLAND ISLANDS (MALVINAS)',
			'FO' => 'FAROE ISLANDS',
			'FJ' => 'FIJI',
			'FI' => 'FINLAND',
			'FR' => 'FRANCE',
			'FX' => 'FRANCE, METROPOLITAN',
			'GF' => 'FRENCH GUIANA',
			'PF' => 'FRENCH POLYNESIA',
			'TF' => 'FRENCH SOUTHERN TERRITORIES',
			'GA' => 'GABON',
			'GM' => 'GAMBIA',
			'GE' => 'GEORGIA',
			'DE' => 'GERMANY',
			'GH' => 'GHANA',
			'GI' => 'GIBRALTAR',
			'GR' => 'GREECE',
			'GL' => 'GREENLAND',
			'GD' => 'GRENADA',
			'GP' => 'GUADELOUPE',
			'GU' => 'GUAM',
			'GT' => 'GUATEMALA',
			'GN' => 'GUINEA',
			'GW' => 'GUINEA-BISSAU',
			'GY' => 'GUYANA',
			'HT' => 'HAITI',
			'HM' => 'HEARD AND MC DONALD ISLANDS',
			'VA' => 'HOLY SEE (VATICAN CITY STATE)',
			'HN' => 'HONDURAS',
			'HK' => 'HONG KONG',
			'HU' => 'HUNGARY',
			'IS' => 'ICELAND',
			'IN' => 'INDIA',
			'ID' => 'INDONESIA',
			'IR' => 'IRAN (ISLAMIC REPUBLIC OF)',
			'IQ' => 'IRAQ',
			'IE' => 'IRELAND',
			'IL' => 'ISRAEL',
			'IT' => 'ITALY',
			'JM' => 'JAMAICA',
			'JP' => 'JAPAN',
			'JO' => 'JORDAN',
			'KZ' => 'KAZAKHSTAN',
			'KE' => 'KENYA',
			'KI' => 'KIRIBATI',
			'KP' => 'KOREA, DEMOCRATIC PEOPLE\'S REPUBLIC OF',
			'KR' => 'KOREA, REPUBLIC OF',
			'KW' => 'KUWAIT',
			'KG' => 'KYRGYZSTAN',
			'LA' => 'LAO PEOPLE\'S DEMOCRATIC REPUBLIC',
			'LV' => 'LATVIA',
			'LB' => 'LEBANON',
			'LS' => 'LESOTHO',
			'LR' => 'LIBERIA',
			'LY' => 'LIBYAN ARAB JAMAHIRIYA',
			'LI' => 'LIECHTENSTEIN',
			'LT' => 'LITHUANIA',
			'LU' => 'LUXEMBOURG',
			'MO' => 'MACAU',
			'MK' => 'MACEDONIA, THE FORMER YUGOSLAV REPUBLIC OF',
			'MG' => 'MADAGASCAR',
			'MW' => 'MALAWI',
			'MY' => 'MALAYSIA',
			'MV' => 'MALDIVES',
			'ML' => 'MALI',
			'MT' => 'MALTA',
			'MH' => 'MARSHALL ISLANDS',
			'MQ' => 'MARTINIQUE',
			'MR' => 'MAURITANIA',
			'MU' => 'MAURITIUS',
			'YT' => 'MAYOTTE',
			'MX' => 'MEXICO',
			'FM' => 'MICRONESIA, FEDERATED STATES OF',
			'MD' => 'MOLDOVA, REPUBLIC OF',
			'MC' => 'MONACO',
			'MN' => 'MONGOLIA',
			'MS' => 'MONTSERRAT',
			'MA' => 'MOROCCO',
			'MZ' => 'MOZAMBIQUE',
			'MM' => 'MYANMAR',
			'NA' => 'NAMIBIA',
			'NR' => 'NAURU',
			'NP' => 'NEPAL',
			'NL' => 'NETHERLANDS',
			'AN' => 'NETHERLANDS ANTILLES',
			'NC' => 'NEW CALEDONIA',
			'NZ' => 'NEW ZEALAND',
			'NI' => 'NICARAGUA',
			'NE' => 'NIGER',
			'NG' => 'NIGERIA',
			'NU' => 'NIUE',
			'NF' => 'NORFOLK ISLAND',
			'MP' => 'NORTHERN MARIANA ISLANDS',
			'NO' => 'NORWAY',
			'OM' => 'OMAN',
			'PK' => 'PAKISTAN',
			'PW' => 'PALAU',
			'PS' => 'PALESTINIAN TERRITORY, OCCUPIED',
			'PA' => 'PANAMA',
			'PG' => 'PAPUA NEW GUINEA',
			'PY' => 'PARAGUAY',
			'PE' => 'PERU',
			'PH' => 'PHILIPPINES',
			'PN' => 'PITCAIRN',
			'PL' => 'POLAND',
			'PT' => 'PORTUGAL',
			'PR' => 'PUERTO RICO',
			'QA' => 'QATAR',
			'RE' => 'REUNION',
			'RO' => 'ROMANIA',
			'RU' => 'RUSSIAN FEDERATION',
			'RW' => 'RWANDA',
			'KN' => 'SAINT KITTS AND NEVIS',
			'LC' => 'SAINT LUCIA',
			'VC' => 'SAINT VINCENT AND THE GRENADINES',
			'WS' => 'SAMOA',
			'SM' => 'SAN MARINO',
			'ST' => 'SAO TOME AND PRINCIPE',
			'SA' => 'SAUDI ARABIA',
			'SN' => 'SENEGAL',
			'SC' => 'SEYCHELLES',
			'SL' => 'SIERRA LEONE',
			'SG' => 'SINGAPORE',
			'SK' => 'SLOVAKIA (Slovak Republic)',
			'SI' => 'SLOVENIA',
			'SB' => 'SOLOMON ISLANDS',
			'SO' => 'SOMALIA',
			'ZA' => 'SOUTH AFRICA',
			'GS' => 'SOUTH GEORGIA AND THE SOUTH SANDWICH ISLANDS',
			'ES' => 'SPAIN',
			'LK' => 'SRI LANKA',
			'SH' => 'ST. HELENA',
			'PM' => 'ST. PIERRE AND MIQUELON',
			'SD' => 'SUDAN',
			'SR' => 'SURINAME',
			'SJ' => 'SVALBARD AND JAN MAYEN ISLANDS',
			'SZ' => 'SWAZILAND',
			'SE' => 'SWEDEN',
			'CH' => 'SWITZERLAND',
			'SY' => 'SYRIAN ARAB REPUBLIC',
			'TW' => 'TAIWAN, PROVINCE OF CHINA',
			'TJ' => 'TAJIKISTAN',
			'TZ' => 'TANZANIA, UNITED REPUBLIC OF',
			'TH' => 'THAILAND',
			'TG' => 'TOGO',
			'TK' => 'TOKELAU',
			'TO' => 'TONGA',
			'TT' => 'TRINIDAD AND TOBAGO',
			'TN' => 'TUNISIA',
			'TR' => 'TURKEY',
			'TM' => 'TURKMENISTAN',
			'TC' => 'TURKS AND CAICOS ISLANDS',
			'TV' => 'TUVALU',
			'UG' => 'UGANDA',
			'UA' => 'UKRAINE',
			'AE' => 'UNITED ARAB EMIRATES',
			'GB' => 'UNITED KINGDOM',
			'US' => 'UNITED STATES',
			'UM' => 'UNITED STATES MINOR OUTLYING ISLANDS',
			'UY' => 'URUGUAY',
			'UZ' => 'UZBEKISTAN',
			'VU' => 'VANUATU',
			'VE' => 'VENEZUELA',
			'VN' => 'VIET NAM',
			'VG' => 'VIRGIN ISLANDS (BRITISH)',
			'VI' => 'VIRGIN ISLANDS (U.S.)',
			'WF' => 'WALLIS AND FUTUNA ISLANDS',
			'EH' => 'WESTERN SAHARA',
			'YE' => 'YEMEN',
			'YU' => 'YUGOSLAVIA',
			'ZM' => 'ZAMBIA',
			'ZW' => 'ZIMBABWE'
	);

	/**
	 * Get country name by country code
	 * @param $code
	 *
	 * @return bool|string
	 *  Return false when country does not exists and throws an exception  when code is not valid.
	 * @throws youn_exception
	 */
	public static function getCountry($code){
		$code   = strtoupper($code);
		if(strlen($code) > 2){
			throw new youn_exception(null,'Country code must have only two letters');
		}
		if(isset(static::$countries[$code])){
			return static::$countries[$code];
		}
		return false;
	}

	/**
	 * @param $code
	 *
	 * @return bool
	 */
	public static function exists($code){
		return isset(static::$countries[strtoupper($code)]);
	}

	/**
	 * @param $country
	 *
	 * @return bool
	 */
	public static function getCode($country){
		$country= strtoupper($country);
		$keys   = array_keys(static::$countries);
		$count  = count(static::$countries);
		for($i  = 0;$i < $count;$i++){
		    $key= $keys[$i];
		    $val= static::$countries[$key];
			if(trim(substr($val,0,strpos('(',$country))) == $country){
				return $key;
			}
		}
		return false;
	}
}