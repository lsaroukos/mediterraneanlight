import { useEffect, useState } from "react";
import APIUtils from "../../../../assets/src/js/utils/APIUtils";
import { __ } from "@wordpress/i18n";
import { Spinner } from '@wordpress/components';
import StarRating from "../../../../assets/src/js/Components/StarRating";

import { Swiper, SwiperSlide } from "swiper/react";
import { Grid, Autoplay } from "swiper/modules";
import "swiper/css";
import "swiper/css/grid";


export default function FeaturedProductsSlider({ attributes, setAttributes }) {
   
    const [featuredProducts, setFeaturedProducts] = useState(undefined);

    /**
     * fetch products from db
     */
    useEffect(() => {
        APIUtils.get("wc/products/featured").then(response => {
            if (response.status === "success") {
                setFeaturedProducts(response.products || []);
            }
        });
    }, []);

    /**
     * 
     * @param {} product 
     */
    const PriceContainer = ({product})=>{
        const hasDiscount = product.sale_price!=="" && product.regular_price!==product.sale_price;

        return (
            <div className={"product-price"+(hasDiscount ? " has-discount" : "")} >
                { hasDiscount && (
                    <span className="sale-price">{product.sale_price}€</span>
                ) }
                { product.regular_price!=="" && (
                    <span className="regular-price">{product.regular_price}€</span>
                )}
            </div>
        );
    }

    if ( featuredProducts===undefined ) return <Spinner />;

    if ( featuredProducts.length===0 ) return <p>{ __("no featured products found") }</p>;

    return (
        <Swiper
            className="products-swiper"
            modules={[Grid, Autoplay]}
            spaceBetween={20}
            loop={true}
            autoplay={{
                delay: 3000,
                disableOnInteraction: false,
            }}
            breakpoints={{
                //  Mobile
                0: {
                    slidesPerView: 1,
                    grid: {
                        rows: 1,
                    },
                },

                //  Tablet (3 per row)
                640: {
                    slidesPerView: 1,
                    grid: {
                        rows: 3,
                    },
                },

                //  Desktop (6 items -> 2 rows × 3 columns)
                1024: {
                    slidesPerView: 2,
                    grid: {
                        rows: 3,
                    },
                },
            }}
        >
            {featuredProducts.map((product) => (
                <SwiperSlide key={product.id}>
                    <div className="product-card">
                        <a href={product.permalink} aria-label={`link to product ${product.title}`}>
                            <img src={product.thumbnail} alt={product.title} />
                        </a>
                        <div className="product-details">
                            <StarRating rating={product.rating} />
                            <a className="product-title" href={product.permalink}>{product.title}</a>
                            <PriceContainer product={product} />
                        </div>
                    </div>
                </SwiperSlide>
            ))}
        </Swiper>
    );
}