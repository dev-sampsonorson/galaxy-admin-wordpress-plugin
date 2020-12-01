export interface ICountry {
    name: string;
    code: string;
}

export interface IState {
    countryCode: string;
    stateName: string;
    stateCode: string;
}

export default class TeboUtility {

    static getQueryString(params, appendSeparator: boolean = false): string {
        return (appendSeparator ? '?' : '') +  Object.keys(params).map(key => {
            return `${encodeURIComponent(key)}=${encodeURIComponent(params[key])}`;
        }).join('');
    }
    static isChrome() {
        return /Chrome/.test(navigator.userAgent) && /Google Inc/.test(navigator.vendor);
    }

    static isSafari() {
        return /Safari/.test(navigator.userAgent) && /Apple Computer/.test(navigator.vendor);
    }

    static getOffset(el) {
        var _x = 0;
        var _y = 0;
        while (el && !isNaN(el.offsetLeft) && !isNaN(el.offsetTop)) {
            _x += el.offsetLeft - el.scrollLeft;
            _y += el.offsetTop - el.scrollTop;
            // el = el.offsetParent;
            // chrome/safari
            // if ($.browser.webkit) {
            if (TeboUtility.isChrome() || TeboUtility.isSafari()) {
                el = el.parentNode;
            } else {
                // firefox/IE
                el = el.offsetParent;
            }
        }
        return { top: _y, left: _x };
    }

    static simulateClick(el) {
        // Create our event (with options)
        var evt = new MouseEvent('click', {
            bubbles: true,
            cancelable: true,
            view: window
        });
        // If cancelled, don't dispatch our event
        var canceled = !el.dispatchEvent(evt);
    }

    static getClosest(el, selector) {
        for (; el && el !== document; el = el.parentNode) {
            if (el.matches(selector)) return el;
        }
        return null;
    }

    static containsElement(el) {
        return (el === document.body) ? false : document.body.contains(el);
    }

    static containsElementById(id) {
        return document.getElementById(id);
    }

    static hasClass(el, className) {
        return (' ' + el.className + ' ').indexOf(' ' + className + ' ') > -1;
    }

    static getSiblings(el, filter) {
        var siblings: any[] = [];
        var sibling = el.parentNode.firstChild;

        do {
            if (!filter || filter(sibling)) {
                if (sibling.nodeType !== 1 || sibling === el) continue;
                siblings.push(sibling);
            }

        } while (sibling = sibling.nextSibling);

        return siblings;
    }

    static getNextSiblings(el, filter) {
        var siblings: any[] = [];
        while (el = el.nextSibling) {
            if (!filter || filter(el)) {
                if (el.nodeType !== 1) continue;
                siblings.push(el);
            }
        }
        return siblings;
    }

    static getNextSibling(el, filter) {
        while (el = el.nextSibling) {
            if (!filter || filter(el)) {
                if (el.nodeType !== 1) continue;
                return el;
            }
        }
        return null;
    }

    static getPreviousSiblings(el, filter) {
        var siblings: any[] = [];
        while (el = el.previousSibling) {
            if (!filter || filter(el)) {
                if (el.nodeType !== 1) continue;
                siblings.push(el);
            }
        }
        return siblings;
    }

    static getPreviousSibling(el: Node, filter: (el: Node) => boolean) {
        let element: Node | null = el;
        while (element = element.previousSibling) {
            if (!filter || filter(element)) {
                if (element.nodeType !== 1) continue;
                return element;
            }
        }

        return null;
    }

    static getViewportSize() {
        var w, h;
        var element = (document.compatMode === "CSS1Compat") ?
            document.documentElement :
            document.body;

        if (typeof (element.clientWidth) != 'undefined') {
            w = Math.max(element.clientWidth, (!('innerWidth' in window) ? 0 : window.innerWidth) || 0);
            h = Math.max(element.clientHeight, (!('innerHeight' in window) ? 0 : window.innerHeight) || 0);
        }

        return { width: w, height: h };
    }

    static numberFormatter(number, decimals, decimalPoint, thousandSeparator): string {
        var n = !isFinite(+number) ? 0 : +number,
            prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
            sep = (typeof thousandSeparator === 'undefined') ? ',' : thousandSeparator,
            dec = (typeof decimalPoint === 'undefined') ? '.' : decimalPoint,
            toFixedFix = function (n, prec) {
                // Fix for IE parseFloat(0.55).toFixed(0) = 0;
                var k = Math.pow(10, prec);
                return Math.round(n * k) / k;
            },
            s = (prec ? toFixedFix(n, prec) : Math.round(n)).toString().split('.');
        if (s[0].length > 3) {
            s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
        }
        if ((s[1] || '').length < prec) {
            s[1] = s[1] || '';
            s[1] += new Array(prec - s[1].length + 1).join('0');
        }
        return s.join(dec);
    }


    static readonly allCountries: ICountry[] = [
        { "name": "Afghanistan", "code": "AF" },
        { "name": "land Islands", "code": "AX" },
        { "name": "Albania", "code": "AL" },
        { "name": "Algeria", "code": "DZ" },
        { "name": "American Samoa", "code": "AS" },
        { "name": "AndorrA", "code": "AD" },
        { "name": "Angola", "code": "AO" },
        { "name": "Anguilla", "code": "AI" },
        { "name": "Antarctica", "code": "AQ" },
        { "name": "Antigua and Barbuda", "code": "AG" },
        { "name": "Argentina", "code": "AR" },
        { "name": "Armenia", "code": "AM" },
        { "name": "Aruba", "code": "AW" },
        { "name": "Australia", "code": "AU" },
        { "name": "Austria", "code": "AT" },
        { "name": "Azerbaijan", "code": "AZ" },
        { "name": "Bahamas", "code": "BS" },
        { "name": "Bahrain", "code": "BH" },
        { "name": "Bangladesh", "code": "BD" },
        { "name": "Barbados", "code": "BB" },
        { "name": "Belarus", "code": "BY" },
        { "name": "Belgium", "code": "BE" },
        { "name": "Belize", "code": "BZ" },
        { "name": "Benin", "code": "BJ" },
        { "name": "Bermuda", "code": "BM" },
        { "name": "Bhutan", "code": "BT" },
        { "name": "Bolivia", "code": "BO" },
        { "name": "Bosnia and Herzegovina", "code": "BA" },
        { "name": "Botswana", "code": "BW" },
        { "name": "Bouvet Island", "code": "BV" },
        { "name": "Brazil", "code": "BR" },
        { "name": "British Indian Ocean Territory", "code": "IO" },
        { "name": "Brunei Darussalam", "code": "BN" },
        { "name": "Bulgaria", "code": "BG" },
        { "name": "Burkina Faso", "code": "BF" },
        { "name": "Burundi", "code": "BI" },
        { "name": "Cambodia", "code": "KH" },
        { "name": "Cameroon", "code": "CM" },
        { "name": "Canada", "code": "CA" },
        { "name": "Cape Verde", "code": "CV" },
        { "name": "Cayman Islands", "code": "KY" },
        { "name": "Central African Republic", "code": "CF" },
        { "name": "Chad", "code": "TD" },
        { "name": "Chile", "code": "CL" },
        { "name": "China", "code": "CN" },
        { "name": "Christmas Island", "code": "CX" },
        { "name": "Cocos (Keeling) Islands", "code": "CC" },
        { "name": "Colombia", "code": "CO" },
        { "name": "Comoros", "code": "KM" },
        { "name": "Congo", "code": "CG" },
        { "name": "Congo, The Democratic Republic of the", "code": "CD" },
        { "name": "Cook Islands", "code": "CK" },
        { "name": "Costa Rica", "code": "CR" },
        { "name": "Cote D'Ivoire", "code": "CI" },
        { "name": "Croatia", "code": "HR" },
        { "name": "Cuba", "code": "CU" },
        { "name": "Cyprus", "code": "CY" },
        { "name": "Czech Republic", "code": "CZ" },
        { "name": "Denmark", "code": "DK" },
        { "name": "Djibouti", "code": "DJ" },
        { "name": "Dominica", "code": "DM" },
        { "name": "Dominican Republic", "code": "DO" },
        { "name": "Ecuador", "code": "EC" },
        { "name": "Egypt", "code": "EG" },
        { "name": "El Salvador", "code": "SV" },
        { "name": "Equatorial Guinea", "code": "GQ" },
        { "name": "Eritrea", "code": "ER" },
        { "name": "Estonia", "code": "EE" },
        { "name": "Ethiopia", "code": "ET" },
        { "name": "Falkland Islands (Malvinas)", "code": "FK" },
        { "name": "Faroe Islands", "code": "FO" },
        { "name": "Fiji", "code": "FJ" },
        { "name": "Finland", "code": "FI" },
        { "name": "France", "code": "FR" },
        { "name": "French Guiana", "code": "GF" },
        { "name": "French Polynesia", "code": "PF" },
        { "name": "French Southern Territories", "code": "TF" },
        { "name": "Gabon", "code": "GA" },
        { "name": "Gambia", "code": "GM" },
        { "name": "Georgia", "code": "GE" },
        { "name": "Germany", "code": "DE" },
        { "name": "Ghana", "code": "GH" },
        { "name": "Gibraltar", "code": "GI" },
        { "name": "Greece", "code": "GR" },
        { "name": "Greenland", "code": "GL" },
        { "name": "Grenada", "code": "GD" },
        { "name": "Guadeloupe", "code": "GP" },
        { "name": "Guam", "code": "GU" },
        { "name": "Guatemala", "code": "GT" },
        { "name": "Guernsey", "code": "GG" },
        { "name": "Guinea", "code": "GN" },
        { "name": "Guinea-Bissau", "code": "GW" },
        { "name": "Guyana", "code": "GY" },
        { "name": "Haiti", "code": "HT" },
        { "name": "Heard Island and Mcdonald Islands", "code": "HM" },
        { "name": "Holy See (Vatican City State)", "code": "VA" },
        { "name": "Honduras", "code": "HN" },
        { "name": "Hong Kong", "code": "HK" },
        { "name": "Hungary", "code": "HU" },
        { "name": "Iceland", "code": "IS" },
        { "name": "India", "code": "IN" },
        { "name": "Indonesia", "code": "ID" },
        { "name": "Iran, Islamic Republic Of", "code": "IR" },
        { "name": "Iraq", "code": "IQ" },
        { "name": "Ireland", "code": "IE" },
        { "name": "Isle of Man", "code": "IM" },
        { "name": "Israel", "code": "IL" },
        { "name": "Italy", "code": "IT" },
        { "name": "Jamaica", "code": "JM" },
        { "name": "Japan", "code": "JP" },
        { "name": "Jersey", "code": "JE" },
        { "name": "Jordan", "code": "JO" },
        { "name": "Kazakhstan", "code": "KZ" },
        { "name": "Kenya", "code": "KE" },
        { "name": "Kiribati", "code": "KI" },
        { "name": "Korea, Democratic People's Republic of", "code": "KP" },
        { "name": "Korea, Republic of", "code": "KR" },
        { "name": "Kuwait", "code": "KW" },
        { "name": "Kyrgyzstan", "code": "KG" },
        { "name": "Lao People's Democratic Republic", "code": "LA" },
        { "name": "Latvia", "code": "LV" },
        { "name": "Lebanon", "code": "LB" },
        { "name": "Lesotho", "code": "LS" },
        { "name": "Liberia", "code": "LR" },
        { "name": "Libyan Arab Jamahiriya", "code": "LY" },
        { "name": "Liechtenstein", "code": "LI" },
        { "name": "Lithuania", "code": "LT" },
        { "name": "Luxembourg", "code": "LU" },
        { "name": "Macao", "code": "MO" },
        { "name": "Macedonia, The Former Yugoslav Republic of", "code": "MK" },
        { "name": "Madagascar", "code": "MG" },
        { "name": "Malawi", "code": "MW" },
        { "name": "Malaysia", "code": "MY" },
        { "name": "Maldives", "code": "MV" },
        { "name": "Mali", "code": "ML" },
        { "name": "Malta", "code": "MT" },
        { "name": "Marshall Islands", "code": "MH" },
        { "name": "Martinique", "code": "MQ" },
        { "name": "Mauritania", "code": "MR" },
        { "name": "Mauritius", "code": "MU" },
        { "name": "Mayotte", "code": "YT" },
        { "name": "Mexico", "code": "MX" },
        { "name": "Micronesia, Federated States of", "code": "FM" },
        { "name": "Moldova, Republic of", "code": "MD" },
        { "name": "Monaco", "code": "MC" },
        { "name": "Mongolia", "code": "MN" },
        { "name": "Montenegro", "code": "ME" },
        { "name": "Montserrat", "code": "MS" },
        { "name": "Morocco", "code": "MA" },
        { "name": "Mozambique", "code": "MZ" },
        { "name": "Myanmar", "code": "MM" },
        { "name": "Namibia", "code": "NA" },
        { "name": "Nauru", "code": "NR" },
        { "name": "Nepal", "code": "NP" },
        { "name": "Netherlands", "code": "NL" },
        { "name": "Netherlands Antilles", "code": "AN" },
        { "name": "New Caledonia", "code": "NC" },
        { "name": "New Zealand", "code": "NZ" },
        { "name": "Nicaragua", "code": "NI" },
        { "name": "Niger", "code": "NE" },
        { "name": "Nigeria", "code": "NG" },
        { "name": "Niue", "code": "NU" },
        { "name": "Norfolk Island", "code": "NF" },
        { "name": "Northern Mariana Islands", "code": "MP" },
        { "name": "Norway", "code": "NO" },
        { "name": "Oman", "code": "OM" },
        { "name": "Pakistan", "code": "PK" },
        { "name": "Palau", "code": "PW" },
        { "name": "Palestinian Territory, Occupied", "code": "PS" },
        { "name": "Panama", "code": "PA" },
        { "name": "Papua New Guinea", "code": "PG" },
        { "name": "Paraguay", "code": "PY" },
        { "name": "Peru", "code": "PE" },
        { "name": "Philippines", "code": "PH" },
        { "name": "Pitcairn", "code": "PN" },
        { "name": "Poland", "code": "PL" },
        { "name": "Portugal", "code": "PT" },
        { "name": "Puerto Rico", "code": "PR" },
        { "name": "Qatar", "code": "QA" },
        { "name": "Reunion", "code": "RE" },
        { "name": "Romania", "code": "RO" },
        { "name": "Russian Federation", "code": "RU" },
        { "name": "RWANDA", "code": "RW" },
        { "name": "Saint Helena", "code": "SH" },
        { "name": "Saint Kitts and Nevis", "code": "KN" },
        { "name": "Saint Lucia", "code": "LC" },
        { "name": "Saint Pierre and Miquelon", "code": "PM" },
        { "name": "Saint Vincent and the Grenadines", "code": "VC" },
        { "name": "Samoa", "code": "WS" },
        { "name": "San Marino", "code": "SM" },
        { "name": "Sao Tome and Principe", "code": "ST" },
        { "name": "Saudi Arabia", "code": "SA" },
        { "name": "Senegal", "code": "SN" },
        { "name": "Serbia", "code": "RS" },
        { "name": "Seychelles", "code": "SC" },
        { "name": "Sierra Leone", "code": "SL" },
        { "name": "Singapore", "code": "SG" },
        { "name": "Slovakia", "code": "SK" },
        { "name": "Slovenia", "code": "SI" },
        { "name": "Solomon Islands", "code": "SB" },
        { "name": "Somalia", "code": "SO" },
        { "name": "South Africa", "code": "ZA" },
        { "name": "South Georgia and the South Sandwich Islands", "code": "GS" },
        { "name": "Spain", "code": "ES" },
        { "name": "Sri Lanka", "code": "LK" },
        { "name": "Sudan", "code": "SD" },
        { "name": "Suriname", "code": "SR" },
        { "name": "Svalbard and Jan Mayen", "code": "SJ" },
        { "name": "Swaziland", "code": "SZ" },
        { "name": "Sweden", "code": "SE" },
        { "name": "Switzerland", "code": "CH" },
        { "name": "Syrian Arab Republic", "code": "SY" },
        { "name": "Taiwan, Province of China", "code": "TW" },
        { "name": "Tajikistan", "code": "TJ" },
        { "name": "Tanzania, United Republic of", "code": "TZ" },
        { "name": "Thailand", "code": "TH" },
        { "name": "Timor-Leste", "code": "TL" },
        { "name": "Togo", "code": "TG" },
        { "name": "Tokelau", "code": "TK" },
        { "name": "Tonga", "code": "TO" },
        { "name": "Trinidad and Tobago", "code": "TT" },
        { "name": "Tunisia", "code": "TN" },
        { "name": "Turkey", "code": "TR" },
        { "name": "Turkmenistan", "code": "TM" },
        { "name": "Turks and Caicos Islands", "code": "TC" },
        { "name": "Tuvalu", "code": "TV" },
        { "name": "Uganda", "code": "UG" },
        { "name": "Ukraine", "code": "UA" },
        { "name": "United Arab Emirates", "code": "AE" },
        { "name": "United Kingdom", "code": "GB" },
        { "name": "United States", "code": "US" },
        { "name": "United States Minor Outlying Islands", "code": "UM" },
        { "name": "Uruguay", "code": "UY" },
        { "name": "Uzbekistan", "code": "UZ" },
        { "name": "Vanuatu", "code": "VU" },
        { "name": "Venezuela", "code": "VE" },
        { "name": "Viet Nam", "code": "VN" },
        { "name": "Virgin Islands, British", "code": "VG" },
        { "name": "Virgin Islands, U.S.", "code": "VI" },
        { "name": "Wallis and Futuna", "code": "WF" },
        { "name": "Western Sahara", "code": "EH" },
        { "name": "Yemen", "code": "YE" },
        { "name": "Zambia", "code": "ZM" },
        { "name": "Zimbabwe", "code": "ZW" }
    ];

    static readonly allStates: IState[] = [
        { stateName: "Abia", stateCode: "NG-AB", countryCode: "NG" },
        { stateName: "Abuja Federal Capital Territory", stateCode: "NG-FC", countryCode: "NG" },
        { stateName: "Adamawa", stateCode: "NG-AD", countryCode: "NG" },
        { stateName: "Akwa Ibom", stateCode: "NG-AK", countryCode: "NG" },
        { stateName: "Anambra", stateCode: "NG-AN", countryCode: "NG" },
        { stateName: "Bauchi", stateCode: "NG-BA", countryCode: "NG" },
        { stateName: "Bayelsa", stateCode: "NG-BY", countryCode: "NG" },
        { stateName: "Benue", stateCode: "NG-BE", countryCode: "NG" },
        { stateName: "Borno", stateCode: "NG-BO", countryCode: "NG" },
        { stateName: "Cross River", stateCode: "NG-CR", countryCode: "NG" },
        { stateName: "Delta", stateCode: "NG-DE", countryCode: "NG" },
        { stateName: "Ebonyi", stateCode: "NG-EB", countryCode: "NG" },
        { stateName: "Edo", stateCode: "NG-ED", countryCode: "NG" },
        { stateName: "Ekiti", stateCode: "NG-EK", countryCode: "NG" },
        { stateName: "Enugu", stateCode: "NG-EN", countryCode: "NG" },
        { stateName: "Gombe", stateCode: "NG-GO", countryCode: "NG" },
        { stateName: "Imo", stateCode: "NG-IM", countryCode: "NG" },
        { stateName: "Jigawa", stateCode: "NG-JI", countryCode: "NG" },
        { stateName: "Kaduna", stateCode: "NG-KD", countryCode: "NG" },
        { stateName: "Kano", stateCode: "NG-KN", countryCode: "NG" },
        { stateName: "Katsina", stateCode: "NG-KT", countryCode: "NG" },
        { stateName: "Kebbi", stateCode: "NG-KE", countryCode: "NG" },
        { stateName: "Kogi", stateCode: "NG-KO", countryCode: "NG" },
        { stateName: "Kwara", stateCode: "NG-KW", countryCode: "NG" },
        { stateName: "Lagos", stateCode: "NG-LA", countryCode: "NG" },
        { stateName: "Nasarawa", stateCode: "NG-NA", countryCode: "NG" },
        { stateName: "Niger", stateCode: "NG-NI", countryCode: "NG" },
        { stateName: "Ogun", stateCode: "NG-OG", countryCode: "NG" },
        { stateName: "Ondo", stateCode: "NG-ON", countryCode: "NG" },
        { stateName: "Osun", stateCode: "NG-OS", countryCode: "NG" },
        { stateName: "Oyo", stateCode: "NG-OY", countryCode: "NG" },
        { stateName: "Plateau", stateCode: "NG-PL", countryCode: "NG" },
        { stateName: "Rivers", stateCode: "NG-RI", countryCode: "NG" },
        { stateName: "Sokoto", stateCode: "NG-SO", countryCode: "NG" },
        { stateName: "Taraba", stateCode: "NG-TA", countryCode: "NG" },
        { stateName: "Yobe", stateCode: "NG-YO", countryCode: "NG" },
        { stateName: "Zamfara", stateCode: "NG-ZA", countryCode: "NG" },























        { stateName: "Adamawa", stateCode: "", countryCode: "" },
        { stateName: "Anambra", stateCode: "", countryCode: "" },
        { stateName: "Akwa Ibom", stateCode: "", countryCode: "" },
        { stateName: "Bauchi", stateCode: "", countryCode: "" },
        { stateName: "Bayelsa", stateCode: "", countryCode: "" },
        { stateName: "Benue", stateCode: "", countryCode: "" },
        { stateName: "Borno", stateCode: "", countryCode: "" },
        { stateName: "Cross River", stateCode: "", countryCode: "" },
        { stateName: "Delta", stateCode: "", countryCode: "" },
        { stateName: "Ebonyi", stateCode: "", countryCode: "" },
        { stateName: "Enugu", stateCode: "", countryCode: "" },
        { stateName: "Edo", stateCode: "", countryCode: "" },
        { stateName: "Ekiti", stateCode: "", countryCode: "" },
        { stateName: "FCT - Abuja", stateCode: "", countryCode: "" },
        { stateName: "Gombe", stateCode: "", countryCode: "" },
        { stateName: "Imo", stateCode: "", countryCode: "" },
        { stateName: "Jigawa", stateCode: "", countryCode: "" },
        { stateName: "Kaduna", stateCode: "", countryCode: "" },
        { stateName: "Kano", stateCode: "", countryCode: "" },
        { stateName: "Katsina", stateCode: "", countryCode: "" },
        { stateName: "Kebbi", stateCode: "", countryCode: "" },
        { stateName: "Kogi", stateCode: "", countryCode: "" },
        { stateName: "Kwara", stateCode: "", countryCode: "" },
        { stateName: "Lagos", stateCode: "", countryCode: "" },
        { stateName: "Nasarawa", stateCode: "", countryCode: "" },
        { stateName: "Niger", stateCode: "", countryCode: "" },
        { stateName: "Ogun", stateCode: "", countryCode: "" },
        { stateName: "Ondo", stateCode: "", countryCode: "" },
        { stateName: "Osun", stateCode: "", countryCode: "" },
        { stateName: "Oyo", stateCode: "", countryCode: "" },
        { stateName: "Plateau", stateCode: "", countryCode: "" },
        { stateName: "Rivers", stateCode: "", countryCode: "" },
        { stateName: "Sokoto", stateCode: "", countryCode: "" },
        { stateName: "Taraba", stateCode: "", countryCode: "" },
        { stateName: "Yobe", stateCode: "", countryCode: "" },
        { stateName: "Zamfara", stateCode: "", countryCode: "" }

    ];

}