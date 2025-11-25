import { ReactComponent as GridIcon } from '../../../../assets/static/img/grid.svg';
import { ReactComponent as Grid2Icon } from '../../../../assets/static/img/grid-2.svg';
import { ReactComponent as ListIcon } from '../../../../assets/static/img/list.svg';
import TogglerUtils from "./TogglerUtils";
import { useEffect, useState } from 'react';

export default function TogglerButtons(){
 
    const [layout, setLayout] = useState("");

    useEffect(()=>{
        setLayout( TogglerUtils.getLayout() );
    },[]);

    const toggleLayout = ( newLayout)=>{
        TogglerUtils.switchLayout(newLayout);
        setLayout( newLayout );
    }

    return (
        <div className="shop-layout-toggler">
            <GridIcon data-layout="grid"   className={(layout==="grid" ? "active" : "" )}   onClick={ ()=>toggleLayout('grid') } />
            <Grid2Icon data-layout="grid2" className={(layout==="grid2" ? "active" : "" )}  onClick={ ()=>toggleLayout('grid2') } />
            <ListIcon data-layout="list"   className={(layout==="list" ? "active" : "" )}   onClick={ ()=>toggleLayout('list') } />
        </div>
    )
}