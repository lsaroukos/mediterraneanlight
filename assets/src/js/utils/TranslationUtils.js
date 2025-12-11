import en from "../../translations/en.json";
import el from "../../translations/el.json";

export default class TranslationUtils{

    static getCurrentLang = ()=>{
        const langInput = document.querySelector("input[name='_lang']");
        return (document.documentElement.lang ?? langInput?.value) ?? "el";
    }

    /**
     * 
     * @param {string} lang 
     * @returns string
     */
    static langToLocale = ( lang )=>{
         const map = {
            el: "el-GR",
            en: "en-GB",
            fr: "fr-FR",
            de: "de-DE"
        };

        return map[lang] || lang;
    }
  
   
}

/**
 * Translates string to current lang
 * @param string key
 * @param string lang
 * 
 * @returns string
 */
export function trns( key, lang=null ){
    
    if( lang===null )
        lang = TranslationUtils.getCurrentLang();

    const translations = { en, el };

    return translations[lang]?.[key] || key;

}