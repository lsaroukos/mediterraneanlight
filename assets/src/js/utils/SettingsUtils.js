import themeJSON from "../../../../theme.json";

export default class SettingsUtils{

    /**
     * 
     * @param {string} name mobile|tablet|laptop|desktop 
     * @returns 
     */
    static getBreakPoint = (name) => {
        return themeJSON?.settings?.custom?.breakpoints?.[name] ?? null;
    }
}