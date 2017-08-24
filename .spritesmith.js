'use strict';

var util = require('util');

module.exports = [{
  // 元となる画像
  src: './src/img/sprite-materials/*.{png,gif,jpg}',
  // 生成されるスプライト画像
  destImage: './src/img/sprite.png',
  // 生成されるスプライト情報のCSSファイル
  destCSS: './src/js/sprite.json',
  // 使用するテンプレート
  cssTemplate: require('spritesmith-texturepacker'),
  // 個々の画像の間隔[px]
  padding: 4,
  // 画像の並べ方
  algorithmOpts: { sort: false },
}];
