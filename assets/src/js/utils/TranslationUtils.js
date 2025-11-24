export default class TranslationUtils{

    static getCurrentLang = ()=>{
        return document.documentElement.lang;
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