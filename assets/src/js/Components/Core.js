import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { initCoreSettings } from "../redux/slices/CoreSlice";

export default function Core(){

    const dispatch = useDispatch();

    useEffect(()=>{
        
        dispatch( initCoreSettings() );

    },[]);

}