import PostUtils from "./PostUtils";

export default class APIUtils{

    /**
     * 
     * @returns get theme root endpoint
     */
    static get_api_root(){
        let root_address = window.location.protocol + '//' + window.location.host;
        //get loaded <header></header> links
        return root_address + '/wp-json/medlight/v1/';
    }

    /**
     * 
     * @param {string} url 
     * @param {boolean} json 
     * @returns | null
     */
    static async get( url, json=true, headers=null) {
        
        try{

            url = url.includes("/wp-json/") ? url : this.get_api_root() + url;
            const response = await fetch( url,{
                method: "GET",
                headers: headers ?? APIUtils.get_auth_headers(),
                credentials: 'same-origin',  // This ensures cookies are sent along with the request
            });
    
            if( json )
                return response.json();
            else
                return response;
        }catch( e ){
            throw new Error(e);
        }
    }


    /**
     * 
     * @param {string} url 
     * @param {boolean} json 
     * @returns 
     */
    static async delete( url, json=true, headers=null) {
        
        try{
        
            url = url.includes("/wp-json/") ? url : this.get_api_root() + url;
        
            const response = await fetch( url,{
                method: "DELETE",
                headers: headers ?? APIUtils.get_auth_headers(),
                credentials: 'same-origin',  // This ensures cookies are sent along with the request
            });
            if( json )
                return response.json();
            else
                return response;
        }catch(e){
            throw new Error(e);
        }
    }

    /**
     * 
     * @param {string} url 
     * @param {*} data 
     * @param {boolean} json 
     * @returns 
     */
    static async post(url, data, json=true, headers=null) {
        
        try{
            url = url.includes("/wp-json/") ? url : this.get_api_root() + url;
    
            let options = {
                method: 'POST',
                headers: headers ?? APIUtils.get_auth_headers(),
                body: json ? JSON.stringify(data) : data,
                credentials: 'same-origin',  // This ensures cookies are sent along with the request
            };
    
            const response = await fetch(  url, options );
    
            return response.json();
        }catch(e){
            throw new Error(e);
        }
    }

    /**
     * 
     * @param {string} url 
     * @param {*} data 
     * @param {boolean} json 
     * @returns 
     */
    static async patch(url, data, json=true, headers=null) {
        
        try{
            url = url.includes("/wp-json/") ? url : this.get_api_root() + url;
    
            let options = {
                method: 'PATCH',
                headers: headers ?? APIUtils.get_auth_headers(),
                body: JSON.stringify(data),
                credentials: 'same-origin',  // This ensures cookies are sent along with the request
            };
    
            const response = await fetch(  url, options );
    
            if( json )
                return response.json();
            else
                return response;
        }catch(e){
            throw new Error(e);
        }
    }

    /**
    * retrieves jwt value if presents and append it to the request headers
    * 
    * @returns Headers 
    */
    static get_auth_headers(){
        
        var headers = new Headers();
        headers.append('Content-Type', 'application/json'); // Specify content type as JSON

        //get value from jwt input...
        let auth_token_inp = document.querySelector('input[name="jwt"]');

        //...if one exits
        if( auth_token_inp ){
            let jwt_token = auth_token_inp.value;         
            //append to headers
            headers.append("X-Authentication-Token", jwt_token);
        }

        //get value from nonce input...
        let nonce_inp = document.querySelector('input[name="_wpnonce"]');

        let nonce = '';
        if( typeof wpApiSettings !== 'undefined' && wpApiSettings?.nonce ){  // wp admin pages nonce
            //if nonce input does not exists, try to get it from wpApiSettings
            nonce = wpApiSettings?.nonce || '';
            headers.append("X-WP-Nonce", nonce);
        }else if( nonce_inp ){
            nonce =  nonce_inp.value;  
            headers.append("X-WP-Nonce", nonce);
        }

        
        let postid = PostUtils.get_post_id();
        //append to headers
        headers.append("X-Post-ID", postid);
       
        //get value from lang input...
        let lang_inp = document.querySelector('input[name="_lang"]');

        let lang = '';
        if( lang_inp ){
            lang =  lang_inp.value;  
            headers.append("X-WP-Lang", lang);
        }
                
        return headers;
    }       

}