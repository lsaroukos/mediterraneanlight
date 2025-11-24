export default class PostUtils{

    /**
    * Retrieves current post id
    * 
    * @returns int
    */
    static get_post_id(){
        
        //get current post id
        let postid_inp = document.querySelector('input[name="medlight_post_id"]');

        if( postid_inp){
            return postid_inp.value;
        }
        return 0;
    }       

}