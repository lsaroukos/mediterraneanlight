import { createSlice } from '@reduxjs/toolkit';
import APIUtils from '../../utils/APIUtils';

const initialState = {
	links: {},
	lang: ""
};

/**
 * register cart reducers
 */
const coreSlice = createSlice({
	name: 'medlight/core',
	initialState,
	reducers: {
		setLinks( state, action ){ 
			state.links = action.payload; 
		},	// defines links from backend
	},
});

export const { setLinks } = coreSlice.actions;

// Async action creator
export const initCoreSettings = () => async (dispatch) => {
	const endpoint = "core";

	APIUtils.get( endpoint ).then( response=>{
		if( response.status==="success" )
			dispatch( setLinks(response.settings.links) );
	}).catch( error => console.error(error) );
};



export default coreSlice.reducer;