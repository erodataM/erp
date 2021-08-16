var path = require('path')
var webpack = require('webpack')
var VueLoaderPlugin = require('vue-loader').VueLoaderPlugin

module.exports = {
  entry: './src/main.js',
  output: {
    path: path.resolve(__dirname, './dist'),
    publicPath: '/dist/',
    filename: 'build.js'
  },
  performance: {
    hints: false
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        use: {
          loader: 'vue-loader'
        }
      },
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader'
        }
      },
      {
        test: /\.css$/i,
        use: ["style-loader", "css-loader"],
      },
      {
        test: /\.(png|jpe?g|gif)$/i,
        use: [
          {
            loader: 'file-loader',
          },
        ],
      },
    ]
  },
  resolve: {
    extensions: ['.js', '.jsx', '.css'],
    alias: {
      'vue$': 'vue/dist/vue.esm-bundler.js'
    }
  },
  devServer: {
    historyApiFallback: true,
    noInfo: true,
    proxy: [
      {
        context: ['/auth', '/api', '/select'],
        target: 'http://api:80',
      },
    ]
  },
  devtool: false,
  plugins: [
    new VueLoaderPlugin()
  ]
}