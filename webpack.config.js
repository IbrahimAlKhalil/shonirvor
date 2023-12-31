const VueLoaderPlugin = require('vue-loader/lib/plugin');
const path = require('path');
const CopyWebpack = require('copy-webpack-plugin');

module.exports = {
    entry: {
        'frontend/home': './resources/js/frontend/home.js',
        'frontend/filter': './resources/js/frontend/filter.js',
        'frontend/registration/ind-service/index': './resources/js/frontend/registration/ind-service/index.js',
        'frontend/registration/org-service/index': './resources/js/frontend/registration/org-service/index.js',
        'frontend/registration/common': './resources/js/frontend/registration/common.js',
        'frontend/ind-service': './resources/js/frontend/ind-service.js',
        'frontend/org-service': './resources/js/frontend/org-service.js',
        'frontend/applications/top-service/ind': './resources/js/frontend/applications/top-service/ind.js',
        'frontend/applications/ad/create': './resources/js/frontend/applications/ad/create.js',
        'frontend/common': './resources/js/frontend/common.js',
        'frontend/ad/edit': './resources/js/frontend/ad/edit.js',
        'frontend/profile/edit': './resources/js/frontend/profile/edit.js',
        'frontend/my-services/ind-service/edit': './resources/js/frontend/my-services/ind-service/edit.js',
        'frontend/my-services/org-service/edit': './resources/js/frontend/my-services/org-service/edit.js',
        'frontend/chat': './resources/js/frontend/chat/index.js',
        'frontend/channel': './resources/js/frontend/chat/channel.js',

        'backend/dashboard': './resources/js/backend/dashboard.js',
        'backend/contents/registration-instruction': './resources/js/backend/contents/registration-instruction.js',
        'backend/ind-service-request/show': './resources/js/backend/ind-service-request/show.js',
        'backend/contents/slider': './resources/js/backend/contents/slider.js',
        'backend/area/modal': './resources/js/backend/area/modal.js',
        'backend/area/option-loader': './resources/js/backend/area/option-loader.js',
        'backend/request/ad-edit/index': './resources/js/backend/request/index.js',
        'backend/common': './resources/js/backend/common.js',
        'backend/service-filter': './resources/js/backend/service-filter.js',
        'backend/user-filter': './resources/js/backend/user-filter.js',

        'errors/404': './resources/js/errors/404.js'
    },
    output: {
        path: path.resolve(__dirname, 'public/assets/js'),
        filename: '[name].bundle.js'
    },
    resolve: {
        extensions: ['.js', '.scss', '.css', '.vue'],
        alias: {
            vue: 'vue/dist/vue.js'
        }
    },
    module: {
        rules: [
            {
                test: /\.vue$/,
                loader: 'vue-loader'
            },
            {
                test: /\.css$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'postcss-loader'
                ]
            },
            {
                test: /\.scss$/,
                use: [
                    'vue-style-loader',
                    'css-loader',
                    'postcss-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.js$/,
                exclude: /(node_modules)/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: ['@babel/preset-env']
                    }
                }
            },
            {
                test: /\.(png|svg|jpg|gif)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[sha512:hash:base64:7].[ext]',
                            outputPath: './../images/',
                            publicPath: '/assets/images'
                        }
                    }
                ]
            }
        ]
    },
    plugins: [
        new VueLoaderPlugin(),
        new CopyWebpack([
            {from: './resources/js/backend/contents/skins', to: 'backend/contents/skins'}
        ])
    ],
    performance: {
        maxAssetSize: 500000,
        maxEntrypointSize: 800000
    },
    externals: {
        jquery: 'jQuery'
    },
    devtool: 'source-map'
};
