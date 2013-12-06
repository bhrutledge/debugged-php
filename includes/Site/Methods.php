<?php

require_once 'Site/Config.php';

/**
 * Static utility class
 *
 * @package Site
 * @author Brian Rutledge <brian@debugged.org>
 */
class Site_Methods
{
    /**
     * Private constructor
     * @return void
     */
    private function __construct()
    {}    
    
    public static function localPath($path)
    {
        return SITE_ROOT_DIR . "/$path";
    }
    
    public static function loadXmlData($name)
    {
        $data_doc = new DOMDocument();
        $data_doc->load(self::localPath("data/$name.xml"));
        return $data_doc;
    }
    
    public static function loadHtmlData($name)
    {
        $data_doc = new DOMDocument();        
        $data_doc->loadHTMLFile(self::localPath("data/$name.html"));
        return $data_doc;
    }
    
    public static function loadYamlData($name)
    {
        require_once 'Site/sfYaml.php';
        return sfYaml::load(self::localPath("data/$name.yml"));
    }
    
    public static function loadXmlFeed($url)
    {
        $error = '';
        $feed = '';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $feed_data = curl_exec($ch);
        if (!$feed_data) {
            $error = curl_error($ch);
        }
        
        curl_close($ch);
        
        if (!$error) {
            libxml_use_internal_errors(true);
            $feed = simplexml_load_string($feed_data);
            if (!$feed) {
                $error = 'Error parsing XML feed ' . $url;
            }
        }
        
        if ($error) {
            error_log($error);
            return false;
        }
        
        return $feed;
    }
    
    public static function loadJson($url)
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        $json = curl_exec($ch);
        if (!$json) {
            $error = curl_error($ch);
        }
        
        curl_close($ch);
        
        if ($error) {
            error_log($error);
            return false;
        }
        
        return json_decode($json, true);
    }
    
    function nodeHtml($node)
    {
        $doc = new DOMDocument();
        $doc->appendChild($doc->importNode($node, true));
        return $doc->saveHTML();
    }
    
    public static function stripSize($html)
    {
        $patterns = array('/width="(\d*)" height="(\d*)"/',
                          '/height="(\d*)" width="(\d*)"/');

        return preg_replace($patterns, array('', ''), $html);
    }
    
    public static function slug($text)
    {
        $slug = ereg_replace('[^ A-Za-z0-9]', '', $text);
        $slug = ereg_replace('[ ]+', ' ', $slug);
        $slug = str_replace(' ', '-', $slug);
        return strtolower($slug);
    }

    /**
     * Generates a site-accessible URL for a page
     * @param string $page
     * @return string  the URL for the page
     */
    public static function url($page, $external=false)
    {
        $url = Site_Config::instance()->host['web_base'] . $page;
        if ($external) {
            $url = 'http://' . 
                   Site_Config::instance()->host['web_domain'] .
                   $url;
        }
        
        return $url;
    } 
    
    /**
     * Sends mail using the site configuration
     *
     * Reads the configuration from the 'email' property of Site_Config.  
     * Required values are:
     *
     * contact_email - The destination address for all site-generated email
     * site_email - The source address for all site-generated email
     * email_prefix - Prepended to the supplied subject
     * smtp_params - An array of SMTP parameters as expected by the SMTP 
     *               protocol in the Mail package
     *
     * @param string $subject
     * @param string $body
     * @param string $from
     * @return boolean  true if the mail was sent (errors are logged)
     */
    public static function mail($subject, $body, $from=null)
    {
        require_once 'Mail.php';
        $config = Site_Config::instance()->email;
        
        $to = $config['contact_email'];
        $headers = array('To' => $to);
        
        if (array_key_exists('email_prefix', $config)) {
            $headers['Subject'] = '[' . $config['email_prefix'] . '] ' 
                                  . $subject;
        }
        else { 
            $headers['Subject'] = $subject;
        }
        
        if (empty($from)) {
            $headers['From'] = $config['site_email'];
        }
        else {
            $headers['From'] = $from;
            $headers['Sender'] = $config['site_email'];
        }
       
        $mailer =& Mail::factory('smtp', $config['smtp_params']);        
        $result = $mailer->send($to, $headers, $body);

        if (PEAR::isError($result)) {
            error_log('PHP: ' . $result->getMessage());
            return false;
        }
        
        return true;
    }
    
    public static function countries()
    {
        return array(
            'AF'=>'Afghanistan',
            'AL'=>'Albania',
            'DZ'=>'Algeria',
            'AS'=>'American Samoa',
            'AD'=>'Andorra',
            'AO'=>'Angola',
            'AI'=>'Anguilla',
            'AQ'=>'Antarctica',
            'AG'=>'Antigua And Barbuda',
            'AR'=>'Argentina',
            'AM'=>'Armenia',
            'AW'=>'Aruba',
            'AU'=>'Australia',
            'AT'=>'Austria',
            'AZ'=>'Azerbaijan',
            'BS'=>'Bahamas',
            'BH'=>'Bahrain',
            'BD'=>'Bangladesh',
            'BB'=>'Barbados',
            'BY'=>'Belarus',
            'BE'=>'Belgium',
            'BZ'=>'Belize',
            'BJ'=>'Benin',
            'BM'=>'Bermuda',
            'BT'=>'Bhutan',
            'BO'=>'Bolivia',
            'BA'=>'Bosnia And Herzegovina',
            'BW'=>'Botswana',
            'BV'=>'Bouvet Island',
            'BR'=>'Brazil',
            'IO'=>'British Indian Ocean Territory',
            'BN'=>'Brunei',
            'BG'=>'Bulgaria',
            'BF'=>'Burkina Faso',
            'BI'=>'Burundi',
            'KH'=>'Cambodia',
            'CM'=>'Cameroon',
            'CA'=>'Canada',
            'CV'=>'Cape Verde',
            'KY'=>'Cayman Islands',
            'CF'=>'Central African Republic',
            'TD'=>'Chad',
            'CL'=>'Chile',
            'CN'=>'China',
            'CX'=>'Christmas Island',
            'CC'=>'Cocos (Keeling) Islands',
            'CO'=>'Columbia',
            'KM'=>'Comoros',
            'CG'=>'Congo',
            'CK'=>'Cook Islands',
            'CR'=>'Costa Rica',
            'CI'=>'Cote D\'Ivorie (Ivory Coast)',
            'HR'=>'Croatia (Hrvatska)',
            'CU'=>'Cuba',
            'CY'=>'Cyprus',
            'CZ'=>'Czech Republic',
            'CD'=>'Democratic Republic Of Congo (Zaire)',
            'DK'=>'Denmark',
            'DJ'=>'Djibouti',
            'DM'=>'Dominica',
            'DO'=>'Dominican Republic',
            'TP'=>'East Timor',
            'EC'=>'Ecuador',
            'EG'=>'Egypt',
            'SV'=>'El Salvador',
            'GQ'=>'Equatorial Guinea',
            'ER'=>'Eritrea',
            'EE'=>'Estonia',
            'ET'=>'Ethiopia',
            'FK'=>'Falkland Islands (Malvinas)',
            'FO'=>'Faroe Islands',
            'FJ'=>'Fiji',
            'FI'=>'Finland',
            'FR'=>'France',
            'FX'=>'France, Metropolitan',
            'GF'=>'French Guinea',
            'PF'=>'French Polynesia',
            'TF'=>'French Southern Territories',
            'GA'=>'Gabon',
            'GM'=>'Gambia',
            'GE'=>'Georgia',
            'DE'=>'Germany',
            'GH'=>'Ghana',
            'GI'=>'Gibraltar',
            'GR'=>'Greece',
            'GL'=>'Greenland',
            'GD'=>'Grenada',
            'GP'=>'Guadeloupe',
            'GU'=>'Guam',
            'GT'=>'Guatemala',
            'GN'=>'Guinea',
            'GW'=>'Guinea-Bissau',
            'GY'=>'Guyana',
            'HT'=>'Haiti',
            'HM'=>'Heard And McDonald Islands',
            'HN'=>'Honduras',
            'HK'=>'Hong Kong',
            'HU'=>'Hungary',
            'IS'=>'Iceland',
            'IN'=>'India',
            'ID'=>'Indonesia',
            'IR'=>'Iran',
            'IQ'=>'Iraq',
            'IE'=>'Ireland',
            'IL'=>'Israel',
            'IT'=>'Italy',
            'JM'=>'Jamaica',
            'JP'=>'Japan',
            'JO'=>'Jordan',
            'KZ'=>'Kazakhstan',
            'KE'=>'Kenya',
            'KI'=>'Kiribati',
            'KW'=>'Kuwait',
            'KG'=>'Kyrgyzstan',
            'LA'=>'Laos',
            'LV'=>'Latvia',
            'LB'=>'Lebanon',
            'LS'=>'Lesotho',
            'LR'=>'Liberia',
            'LY'=>'Libya',
            'LI'=>'Liechtenstein',
            'LT'=>'Lithuania',
            'LU'=>'Luxembourg',
            'MO'=>'Macau',
            'MK'=>'Macedonia',
            'MG'=>'Madagascar',
            'MW'=>'Malawi',
            'MY'=>'Malaysia',
            'MV'=>'Maldives',
            'ML'=>'Mali',
            'MT'=>'Malta',
            'MH'=>'Marshall Islands',
            'MQ'=>'Martinique',
            'MR'=>'Mauritania',
            'MU'=>'Mauritius',
            'YT'=>'Mayotte',
            'MX'=>'Mexico',
            'FM'=>'Micronesia',
            'MD'=>'Moldova',
            'MC'=>'Monaco',
            'MN'=>'Mongolia',
            'MS'=>'Montserrat',
            'MA'=>'Morocco',
            'MZ'=>'Mozambique',
            'MM'=>'Myanmar (Burma)',
            'NA'=>'Namibia',
            'NR'=>'Nauru',
            'NP'=>'Nepal',
            'NL'=>'Netherlands',
            'AN'=>'Netherlands Antilles',
            'NC'=>'New Caledonia',
            'NZ'=>'New Zealand',
            'NI'=>'Nicaragua',
            'NE'=>'Niger',
            'NG'=>'Nigeria',
            'NU'=>'Niue',
            'NF'=>'Norfolk Island',
            'KP'=>'North Korea',
            'MP'=>'Northern Mariana Islands',
            'NO'=>'Norway',
            'OM'=>'Oman',
            'PK'=>'Pakistan',
            'PW'=>'Palau',
            'PA'=>'Panama',
            'PG'=>'Papua New Guinea',
            'PY'=>'Paraguay',
            'PE'=>'Peru',
            'PH'=>'Philippines',
            'PN'=>'Pitcairn',
            'PL'=>'Poland',
            'PT'=>'Portugal',
            'PR'=>'Puerto Rico',
            'QA'=>'Qatar',
            'RE'=>'Reunion',
            'RO'=>'Romania',
            'RU'=>'Russia',
            'RW'=>'Rwanda',
            'SH'=>'Saint Helena',
            'KN'=>'Saint Kitts And Nevis',
            'LC'=>'Saint Lucia',
            'PM'=>'Saint Pierre And Miquelon',
            'VC'=>'Saint Vincent And The Grenadines',
            'SM'=>'San Marino',
            'ST'=>'Sao Tome And Principe',
            'SA'=>'Saudi Arabia',
            'SN'=>'Senegal',
            'SC'=>'Seychelles',
            'SL'=>'Sierra Leone',
            'SG'=>'Singapore',
            'SK'=>'Slovak Republic',
            'SI'=>'Slovenia',
            'SB'=>'Solomon Islands',
            'SO'=>'Somalia',
            'ZA'=>'South Africa',
            'GS'=>'South Georgia And South Sandwich Islands',
            'KR'=>'South Korea',
            'ES'=>'Spain',
            'LK'=>'Sri Lanka',
            'SD'=>'Sudan',
            'SR'=>'Suriname',
            'SJ'=>'Svalbard And Jan Mayen',
            'SZ'=>'Swaziland',
            'SE'=>'Sweden',
            'CH'=>'Switzerland',
            'SY'=>'Syria',
            'TW'=>'Taiwan',
            'TJ'=>'Tajikistan',
            'TZ'=>'Tanzania',
            'TH'=>'Thailand',
            'TG'=>'Togo',
            'TK'=>'Tokelau',
            'TO'=>'Tonga',
            'TT'=>'Trinidad And Tobago',
            'TN'=>'Tunisia',
            'TR'=>'Turkey',
            'TM'=>'Turkmenistan',
            'TC'=>'Turks And Caicos Islands',
            'TV'=>'Tuvalu',
            'UG'=>'Uganda',
            'UA'=>'Ukraine',
            'AE'=>'United Arab Emirates',
            'UK'=>'United Kingdom',
            'US'=>'United States',
            'UM'=>'United States Minor Outlying Islands',
            'UY'=>'Uruguay',
            'UZ'=>'Uzbekistan',
            'VU'=>'Vanuatu',
            'VA'=>'Vatican City (Holy See)',
            'VE'=>'Venezuela',
            'VN'=>'Vietnam',
            'VG'=>'Virgin Islands (British)',
            'VI'=>'Virgin Islands (US)',
            'WF'=>'Wallis And Futuna Islands',
            'EH'=>'Western Sahara',
            'WS'=>'Western Samoa',
            'YE'=>'Yemen',
            'YU'=>'Yugoslavia',
            'ZM'=>'Zambia',
            'ZW'=>'Zimbabwe'
        );
    }
}
