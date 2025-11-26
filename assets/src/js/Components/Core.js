import { useEffect } from "react";
import { useDispatch } from "react-redux";
import { initCoreSettings } from "../redux/slices/CoreSlice";
import Frontend from "./Frontend";

export default function Core(){

    const dispatch = useDispatch();

    useEffect(()=>{
        
        dispatch( initCoreSettings() );

    },[]);

    return (
        <>
            <Frontend />
        </>
    )

}