const defaultConfig = require('@wordpress/scripts/config/webpack.config');
const { resolve } = require("path");
const { ROOT_DIR } = require('./utils');
const CopyWebpackPlugin = require('copy-webpack-plugin');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const fs = require('fs');



module.exports = (env, argv) => {
    const src = resolve(ROOT_DIR, 'blocks/src/' + env.OUTPUT_DIR);
    const dist = resolve(ROOT_DIR, 'blocks/dist/' + env.OUTPUT_DIR);

    class ModifyViewAssetPlugin {
        apply(compiler) {
            compiler.hooks.afterEmit.tap('ModifyViewAssetPlugin', (compilation) => {
                const viewAssetPath = resolve(dist, 'view.asset.php');
                if (fs.existsSync(viewAssetPath)) {
                    let content = fs.readFileSync(viewAssetPath, 'utf8');
                    // Modify the content to include type => module and proper dependencies
                    content = content.replace(
                        /array\(([^)]+)\)/,
                        "array('dependencies' => array('@wordpress/interactivity'), 'version' => '" + 
                        compilation.hash.slice(0, 20) + "', 'type' => 'module')"
                    );
                    fs.writeFileSync(viewAssetPath, content);
                }
            });
        }
    }


    let extra_rules = [];
    if (process.env.FRONTEND_SCRIPT != null) {
        let fname = process.env.FRONTEND_SCRIPT.replace('file:', '');
        extra_rules.push({
            test: resolve(src, fname),
            use: {
                loader: 'babel-loader',
                options: {
                    presets: [
                        '@babel/preset-env', 
                        ['@babel/preset-react', { 
                            "runtime": "automatic",
                            "importSource": "@wordpress/element"
                        }]
                    ],
                }
            }
        });
    }

    const entry = {
        index: resolve(process.env.WP_SRC_DIRECTORY, 'index.js'),
    };

    const viewPath = resolve(process.env.WP_SRC_DIRECTORY, 'view.js');
    if (fs.existsSync(viewPath)) {
        entry.view = viewPath;
    }

    return {
        ...defaultConfig,
        mode: env.NODE_ENV === 'production' ? 'production' : 'development',
        entry,
        output: {
            filename: '[name].js',
            path: dist,
        //     library: {
        //         type: 'module',
        //     },
        // },
        // experiments: {
        //     outputModule: true,
        },
        plugins: [
            ...defaultConfig.plugins.filter(plugin => 
                plugin.constructor.name !== 'RtlCssPlugin' && 
                plugin.constructor.name !== 'MiniCssExtractPlugin' &&
                plugin.constructor.name !== 'DependencyExtractionWebpackPlugin'
            ),
            new MiniCssExtractPlugin({
                filename: '[name].css',
            }),
            new CopyWebpackPlugin({
                patterns: [
                    {
                        from: '**/*.json',
                        context: process.env.WP_SRC_DIRECTORY,
                        to: dist,
                        noErrorOnMissing: true,
                    }
                ]
            }),
            new DependencyExtractionWebpackPlugin({
                injectPolyfill: false,
                requestToExternal(request) {
                    // Handle the interactivity package
                    if (request === '@wordpress/interactivity') {
                        return ['wp', 'interactivity'];
                    }
                    // Handle other WordPress packages
                    if (request.startsWith('@wordpress/')) {
                        return ['wp', request.substring(11)];
                    }
                },
                requestToHandle(request) {
                    // For view.js, we want to keep '@wordpress/interactivity' as is
                    if (request === '@wordpress/interactivity') {
                        return request;
                    }
                    // For other WordPress packages, use the wp- prefix
                    if (request.startsWith('@wordpress/')) {
                        return 'wp-' + request.substring(11);
                    }
                },
                combineAssets: false,
                outputFormat: 'php',
                combinedOutputFile: null,
            }),
            //new ModifyViewAssetPlugin(),

        ],
        module: {
            rules: [
                ...defaultConfig.module.rules,
                ...extra_rules
            ]
        },
        resolve: {
            ...defaultConfig.resolve,
            // alias: {
            //     ...defaultConfig.resolve.alias,
            //     'react': resolve(ROOT_DIR, 'node_modules/@wordpress/element/react'),
            //     'react-dom': resolve(ROOT_DIR, 'node_modules/@wordpress/element/react-dom')
            // }
        },
        externals: {
            react: 'React',
            'react-dom': 'ReactDOM',
            '@wordpress/interactivity': ['wp', 'interactivity'],
            '@wordpress/blocks': ['wp', 'blocks'],
            '@wordpress/block-editor': ['wp', 'blockEditor'],
            '@wordpress/components': ['wp', 'components'],
            '@wordpress/i18n': ['wp', 'i18n'],
            '@wordpress/element': ['wp', 'element'],
            '@woocommerce/blocks-checkout': ['wc', 'blocksCheckout'],
        },
        watch: false,
        watchOptions: {
            aggregateTimeout: 600,
            ignored: /node_modules/,
        },
    };
};