import TranslationUtils from "./TranslationUtils"

export default class DateTimeUtils{

    /**
     * 
     * @param {string} isoDate e.g. 2025-11-23T11:55:35
     * @param {string} locale   e.g. "en-GB" 
     * @returns string e.g. Νοε 12, 2025
     */
    static isoToLocaleDate = (isoDate, locale="el-GR")=>{
        locale = TranslationUtils.langToLocale( locale );   // ensure locale string

        const formatted = new Intl.DateTimeFormat(locale, {
            month: 'short',
            day: 'numeric',
            year: 'numeric'
        }).format(new Date(isoDate));

        return formatted;
    }
}