const path = require('path');

module.exports = {
    entry: {
        'frontend/home': './resources/assets/js/frontend/home.js',
        'frontend/filter': './resources/assets/js/frontend/filter.js',
        'frontend/ind-service/show': './resources/assets/js/frontend/ind-service/show.js',
        'frontend/org-service/show': './resources/assets/js/frontend/org-service/show.js',

        'backend/dashboard': './resources/assets/js/backend/dashboard.js',
        'backend/contents/registration-instruction': './resources/assets/js/backend/contents/registration-instruction.js',
        'backend/contents/slider': './resources/assets/js/backend/contents/slider.js',

        'errors/404': './resources/assets/js/errors/404.js'
    },
    output: {
        path: path.resolve(__dirname, "public/assets/js"),
        filename: '[name].bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.css$/,
                use: [
                    'style-loader',
                    'css-loader',
                    'postcss-loader'
                ]
            },
            {
                test: /\.scss$/,
                use: [
                    'style-loader',
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
            },
            {
                test: /\.(woff|woff2|eot|ttf|otf)$/,
                use: [
                    {
                        loader: 'file-loader',
                        options: {
                            name: '[sha512:hash:base64:7].[ext]',
                            outputPath: './../fonts/',
                            publicPath: '/assets/fonts'
                        }
                    }
                ]
            }
        ]
    },
    performance: {
        maxAssetSize: 500000,
        maxEntrypointSize: 800000
    },
    devtool: "source-map"
};