<style>
  .color-picker,
  .color-picker:before,
  .color-picker:after,
  .color-picker *,
  .color-picker *:before,
  .color-picker *:after {
    -webkit-box-sizing:border-box;
    -moz-box-sizing:border-box;
    box-sizing:border-box;
  }
  .color-picker {
    position:absolute;
    top:0;
    left:0;
    z-index:9999;
  }
  .color-picker-control {
    border:1px solid #000;
    -webkit-box-shadow:1px 5px 10px rgba(0,0,0,.5);
    -moz-box-shadow:1px 5px 10px rgba(0,0,0,.5);
    box-shadow:1px 5px 10px rgba(0,0,0,.5);
  }
  .color-picker-control *,
  .color-picker-control *:before,
  .color-picker-control *:after {border-color:inherit}
  .color-picker-control:after {
    content:" ";
    display:table;
    clear:both;
  }
  .color-picker i {font:inherit}
  .color-picker-h {
    position:relative;
    width:20px;
    height:150px;
    float:right;
    border-left:1px solid;
    border-left-color:inherit;
    cursor:ns-resize;
    background:transparent url('color-picker-h.png') no-repeat 50% 50%;
    background-image:-webkit-linear-gradient(to top,#f00 0%,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,#f00 100%);
    background-image:-moz-linear-gradient(to top,#f00 0%,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,#f00 100%);
    background-image:linear-gradient(to top,#f00 0%,#ff0 17%,#0f0 33%,#0ff 50%,#00f 67%,#f0f 83%,#f00 100%);
    -webkit-background-size:100% 100%;
    -moz-background-size:100% 100%;
    background-size:100% 100%;
    overflow:hidden;
  }
  .color-picker-h i {
    position:absolute;
    top:-3px;
    right:0;
    left:0;
    z-index:3;
    display:block;
    height:6px;
  }
  .color-picker-h i:before {
    content:"";
    position:absolute;
    top:0;
    right:0;
    bottom:0;
    left:0;
    display:block;
    border:3px solid;
    border-color:inherit;
    border-top-color:transparent;
    border-bottom-color:transparent;
  }
  .color-picker-sv {
    position:relative;
    width:150px;
    height:150px;
    float:left;
    background:transparent url('color-picker-sv.png') no-repeat 50% 50%;
    background-image:-webkit-linear-gradient(to top,#000,rgba(0,0,0,0)),linear-gradient(to right,#fff,rgba(255,255,255,0));
    background-image:-moz-linear-gradient(to top,#000,rgba(0,0,0,0)),linear-gradient(to right,#fff,rgba(255,255,255,0));
    background-image:linear-gradient(to top,#000,rgba(0,0,0,0)),linear-gradient(to right,#fff,rgba(255,255,255,0));
    -webkit-background-size:100% 100%;
    -moz-background-size:100% 100%;
    background-size:100% 100%;
    cursor:crosshair;
  }
  .color-picker-sv i {
    position:absolute;
    top:-4px;
    right:-4px;
    z-index:3;
    display:block;
    width:8px;
    height:8px;
  }
  .color-picker-sv i:before,
  .color-picker-sv i:after {
    content:"";
    position:absolute;
    top:0;
    right:0;
    bottom:0;
    left:0;
    display:block;
    border:1px solid;
    border-color:inherit;
    -webkit-border-radius:100%;
    -moz-border-radius:100%;
    border-radius:100%;
  }
  .color-picker-sv i:before {
    top:-1px;
    right:-1px;
    bottom:-1px;
    left:-1px;
    border-color:#fff;
  }
</style>