'use strict'
const path = require('path')
function resolve (dir) {
    return path.join(__dirname, dir)
}

const port = process.env.port || process.env.npm_config_port || 8080

module.exports = {
    publicPath: process.env.BASE_URL,
    outputDir: 'dist',
    assetsDir: 'static',
    lintOnSave: 'default',
    productionSourceMap: false,
    devServer: {
        port: port,
        // open: true,
        overlay: {
            warnings: false,
            errors: true
        }
    },
    configureWebpack: {
        resolve: {
            alias: {
                '@': resolve('src')
            }
        }
    }
}