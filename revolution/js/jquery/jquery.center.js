/**
 * @author Alexandre Magno
 * @desc Center a element with jQuery
 * @version 1.0
 * @example
 * $("element").center({
 *
 * 		vertical: true,
 *      horizontal: true
 *
 * });
 * @obs With no arguments, the default is above
 * @license free
 * @param bool vertical, bool horizontal
 * @contribution Paulo Radichi
 *
 */
jQuery.fn.center=function(params){var options={vertical:true,horizontal:true}
op=jQuery.extend(options,params);return this.each(function(){var $0=jQuery(this);var width=$0.width();var height=$0.height();var paddingTop=parseInt($0.css("padding-top"));var paddingBottom=parseInt($0.css("padding-bottom"));var borderTop=parseInt($0.css("border-top-width"));var borderBottom=parseInt($0.css("border-bottom-width"));
//ie patch + Math.floor --chorvus
if ( !( borderBottom >= 0 ) ) borderBottom = 0; if ( !( borderTop >= 0 ) ) borderTop = 0;
var mediaBorder=Math.floor((borderTop+borderBottom)/2);var mediaPadding=Math.floor((paddingTop+paddingBottom)/2);var positionType=$0.parent().css("position");var halfWidth=Math.floor(width/2)*(-1);var halfHeight=(Math.floor(height/2)*(-1))-mediaPadding-mediaBorder;var cssProp={};if($0.css("position")!="fixed"){cssProp.position='absolute';}
if(op.vertical){cssProp.height=height;cssProp.top='50%';cssProp.marginTop=halfHeight;}
if(op.horizontal){cssProp.width=width;cssProp.left='50%';cssProp.marginLeft=halfWidth;}
if(positionType=='static'){$0.parent().css("position","relative");}
$0.css(cssProp);});};