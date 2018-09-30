const path = require('path');

module.exports = {
    entry: {
        'frontend/home': './resources/assets/js/frontend/home.js',
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
            }
        ]
    },
    performance: {
        maxAssetSize: 500000,
        maxEntrypointSize: 800000
    },
    devtool: "source-map"
};