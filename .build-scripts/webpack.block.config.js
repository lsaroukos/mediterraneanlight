const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require("path");
const { ROOT_DIR } = require('./utils');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const fs = require('fs');


module.exports = {
    ...defaultConfig,
    externals: {
        ...defaultConfig.externals,
        '@woocommerce/blocks-checkout': ['wc', 'blocksCheckout'],
    },
};