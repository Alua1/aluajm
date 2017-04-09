/*
 * @version   $Id: RokGallery.FileSettings.js 10876 2013-05-30 06:23:01Z btowles $
 * @author    RocketTheme http://www.rockettheme.com
 * @copyright Copyright (C) 2007 - 2016 RocketTheme, LLC
 * @license   http://www.gnu.org/licenses/gpl-2.0.html GNU/GPLv2 only
 */
((function(){this.RokGallery.FileSettings=new Class({Implements:[Options,Events],options:{},initialize:function(c,b){var a=this;
this.setOptions(b);this.element=document.id(c)||document.getElement(c);this.indicator=this.element.getElement(".indicator");this.loader=document.id(this.options.loader)||document.id("file-settings-loader");
this.margins={element:{"margin-top":this.element.getStyle("margin-top"),"margin-bottom":this.element.getStyle("margin-bottom")},loader:{"margin-top":this.loader.getStyle("margin-top"),"margin-bottom":this.loader.getStyle("margin-bottom")}};
this.bounds={load:{onSuccess:this.prepopulate.bind(this)},remove:{onSuccess:this.deleteSliceResponse.bind(this)},publishSlice:{onSuccess:this.publishSliceResponse.bind(this)},arrows:{previous:this.previousSlice.bind(this),next:this.nextSlice.bind(this)},buttons:{"new":this.newSlice.bind(this),edit:this.editSlice.bind(this),share:this.shareSlice.bind(this),"delete":this.deleteSliceRequest.bind(this),publishSlice:this.publishSliceRequest.bind(this),publish:this.publishFile.bind(this),remove:this.removeFile.bind(this)},inputs:{title:this.updateTitle.bind(this),slug:this.updateSlug.bind(this),description:this.updateDescription.bind(this)},document:{click:this.documentClose.bind(this)}};
this.ajax=new Request({url:RokGallery.url,method:"post"}).addEvents(this.bounds.load);this.publishSliceAjax=new Request({url:RokGallery.url,method:"post"}).addEvents(this.bounds.publishSlice);
this.deleteAjax=new Request({url:RokGallery.url,method:"post"}).addEvents(this.bounds.remove);this.fieldsAjax=new Request({url:RokGallery.url,method:"post",data:{model:"File",action:"update"},onRequest:this.updateRequest.bind(this),onSuccess:this.updateSuccess.bind(this)});
this.scroller=new Fx.Scroll(window,{duration:300,transition:"quad:out"});this.slide=new Fx.Slide(this.element,{onStart:function(){if(this.open){a.setOverflow("hidden");
document.removeEvents(a.bounds.document);}else{document.addEvents(a.bounds.document);}},onComplete:function(){a.isOpen=!this.open;if(!this.open){a.setOverflow("visible");
a.scroller.toElementEdge("file-settings","y");}else{var d=a.current.getParent(".gallery-row"),e=d.getNext(".gallery-row");d.removeClass("before-filesettings");
if(e){e.removeClass("after-filesettings");}this.wrapper.setStyle("display","none");RokGallery.rubberband.attach();RokGallery.filters.attach();}}}).hide();
this.slide.wrapper.setStyle("display","none");this.loader.set("tween",{duration:350}).setStyles({opacity:0,visibility:"hidden"});this.current=null;this.id=0;
this.isOpen=false;this.slices=[];this.arrows=this.element.getElements(".slices-wrapper .status .previous, .slices-wrapper .status .next");this.buttons=this.element.getElements(".slices-controls > .button, .slices-controls > .publish-button");
this.inputs=this.element.getElements("#file-settings .info input, #file-settings .info textarea");this.attachEvents();window.addEvent("resize:throttle(150)",this.updateIndicator.bind(this));
},attachEvents:function(){var a=["new","edit","share","delete","publishSlice"];this.buttons.each(function(c,b){c.removeEvents("click");c.addEvent("click",this.bounds.buttons[a[b]]);
},this);this.element.getElement(".editfile-publish, .editfile-unpublish").removeEvents("click");this.element.getElement(".editfile-delete").removeEvents("click");
this.element.getElement(".editfile-publish, .editfile-unpublish").addEvent("click",this.bounds.buttons.publish);this.element.getElement(".editfile-delete").addEvent("click",this.bounds.buttons.remove);
this.element.getElement(".statusbar .editfile-save").removeEvents("click").addEvent("click",this.saveFields.bind(this));["title","slug","description"].each(function(c,b){this.inputs[b].removeEvents("focus");
this.inputs[b].removeEvents("blur");this.inputs[b].addEvent("focus",function(){clearTimeout(this.inputs[b].savingTimer);this.inputs[b].savingTimer=this.bounds.inputs[c].periodical(500,this.bounds.inputs[c],this.inputs[b]);
}.bind(this)).addEvent("blur",function(){clearTimeout(this.inputs[b].savingTimer);this.bounds.inputs[c](this.inputs[b]);}.bind(this));this.inputs[b].store("saved-value",this.inputs[b].get("value"));
},this);document.body.store("filesettings-attached",true);},documentClose:function(f){if(RokGallery.editPanel.isOpen){return;}var c=document.getElement("#file-settings ! div"),a=document.id("popup"),b=document.id("overlay"),d;
d=(f.target!==c&&c.contains(f.target));d=d||(f.target!==a&&a.contains(f.target));d=d||(f.target===b);if(!d){this.element.getElement(".editfile-close").fireEvent("click");
}},setOverflow:function(a){$$(this.slide.wrapper,this.slide.element).setStyle("overflow",a);},inject:function(c){this.current=c;this.id=this.current.retrieve("file-id");
var a=c.getParent(".gallery-row"),b=a.getNext(".gallery-row");this.loader.inject(a,"after");this.slide.wrapper.inject(this.loader,"after");this.indicator.setStyle("left",this.getBlockPosition(c));
a.addClass("before-filesettings");if(b){b.addClass("after-filesettings");}if(document.id("mc-standard")){if(!b){this.loader.setStyle("margin-bottom",-10);
}else{this.loader.setStyle("margin-bottom",0);}}return this;},load:function(){this.loader.setStyles({display:"block",opacity:1,visibility:"visible"});this.slide.wrapper.setStyle("display","none");
this.element.setStyle("display","none");RokGallery.filters.detach();this.ajax.send({data:{model:"File",action:"get",params:JSON.encode({id:this.id})}});
},getBlockPosition:function(c){var a=this.indicator.getSize().x/2,b=c.getSize().x/2;return c.getPosition(c.getParent(".gallery-row")).x+b;},updateIndicator:function(){if(!this.isOpen||!this.current){return;
}this.indicator.setStyle("left",this.getBlockPosition(this.current));},prepopulate:function(a){RokGallery.rubberband.detach();this.element.getElement(".editfile-close").addEvent("click:once",this.beforeClose.bind(this));
this.loader.fade("out").retrieve("tween").chain(function(){this.populate(a);}.bind(this));},populate:function(b){this.loader.setStyle("display","none");
this.slide.wrapper.setStyle("display","block");this.element.setStyle("display","block");if(!JSON.validate(b)){this.element.getElement(".editfile-close").fireEvent("click:once");
return this.popup({title:"File Settings - Invalid Response",message:'<p class="error-intro">The response from the server had an invalid JSON string while trying to load Image Data. Following is the reply.</p>'+b});
}b=JSON.decode(b);if(b.status!="success"){this.element.getElement(".editfile-close").fireEvent("click:once");return this.popup({title:"File Settings - Error",message:b.message});
}var c=b.payload.file,a=b.payload.defaults;["title","description","slug","published","Tags","Slices"].each(function(d){this["set"+d.capitalize()](c[d]);
},this);if(!a.thumb_background){a.thumb_background="";}this.current.store("file-data",c);this.current.store("thumb-defaults",a);return this.open();},open:function(){this.current.retrieve("switcher:filesettings").close.fade("out");
this.slide.slideIn();return this;},beforeClose:function(){if(this.slide.open){this.slide.chain(function(){RokGallery.blocks.enable().unflip(this.current);
}.bind(this));this.close();}else{RokGallery.blocks.enable().unflip(this.current);}},close:function(){this.current.retrieve("switcher:filesettings").close.fade("in");
this.slide.slideOut();return this;},toggle:function(){this.slide.toggle();return this;},setTitle:function(a){this.element.getElement(".info .title input").set("value",a).store("saved-value",a);
},setDescription:function(a){this.element.getElement(".info .description textarea").set("value",a).store("saved-value",a);},setSlug:function(a){this.element.getElement(".info .slug input").set("value",a).store("saved-value",a);
},setPublished:function(a){this.element.getElement(".editfile-publish span > span:last-child, .editfile-unpublish span > span:last-child").set("text",a?"unpublish":"publish");
this.element.getElement(".editfile-publish, .editfile-unpublish").removeClass("editfile-publish").removeClass("editfile-unpublish").addClass("editfile-"+(a?"unpublish":"publish"));
},setTags:function(a){a=a.map(function(b){return b.tag;});this.element.getElements(".tags-list .tag").dispose();delete RokGallery.tags;RokGallery.tags=RokGallery.initTags(".tags.edit-block",this.id);
RokGallery.tags.insertMany(a,true);RokGallery.tags.list.inject(RokGallery.tags.container);if(!a.length){RokGallery.tags.fireEvent("emptyList");}else{RokGallery.tags.fireEvent("nonEmptyList");
}RokGallery.tags.scrollbar.update();},setSlices:function(b){this.slices=b;this.currentSlice=0;var a=this.element.getElements(".count span:last-child");
a.set("text",this.slices.length);this.loadSlice(this.currentSlice);},updateSliceData:function(d,c){var a=this.currentSlice,b=this.slices[a];this.slices[a]=Object.merge(b,d);
this.loadSlice(a,c);},loadSlice:function(a,c){var e=this.slices[a],b=this.element.getElement(".slices .slices-wrapper");var d=this.element.getElements(".count span:last-child");
d.set("text",this.slices.length);b.getElement(".title").set("text",e.title||"");this.loadSliceThumb(a,c);b.getElement(".slice-width").set("text",e.xsize);
b.getElement(".slice-height").set("text",e.ysize);b.getElement(".slice-size").set("text",Uploader.formatUnit(e.filesize,"b"));this.publishSlice(a,e.published);
},loadSliceThumb:function(f,h){var c=this.slices[f],a=this.element.getElement(".slices"),d=a.getElement(".image-wrapper"),b=d.getElement(".image"),g=a.getElement(".count span:first-child"),i=a.getElement(".count span:last-child"),j=a.getElement(".gallery");
b.set("class","image");b.setStyle("background-image","");a.addClass("loader");d.removeClass("admin").removeClass("front");if(c.admin_thumb){d.addClass("admin");
}this.detachArrows();g.set("text",this.currentSlice+1);i.set("text",this.slices.length);if(c.admin_thumb){a.getElement(".slice-delete").setStyle("display","none");
a.getElement(".slice-publish, .slice-unpublish").setStyle("display","none");}else{a.getElement(".slice-delete").setStyle("display","block");a.getElement(".slice-publish, .slice-unpublish").setStyle("display","block");
}if(c.gallery_id){j.set("text","Gallery: "+document.getElement("#file-edit .file-gallery li[data-key="+c.gallery_id+"]").get("text"));}else{j.set("text","");
}var e=(!h)?c.adminthumburl:c.adminthumburl+"?nocache="+Date.now();if(Browser.safari&&h){(2).times(function(){new Asset.image(e);});}new Asset.image(e,{onload:function(){a.removeClass("loader");
b.setStyle("background-image","url("+e+")");this.attachArrows();}.bind(this),onerror:function(){a.removeClass("loader");b.addClass("error");this.attachArrows();
}.bind(this)});if(c.admin_thumb){this.current.getElement(".gallery-thumb-wrapper img").set("src",e);}},update:function(a,b){var c=this.element.getElements(".statusbar .editfile-save"),e=a.target||a,d=e.retrieve("saved-value");
if(d!=e.get("value")){c.setStyle("display","inline-block");}},saveFields:function(){if(this.fieldsAjax.isRunning()){return;}var a={};["title","slug","description"].each(function(e,d){a[e]=this.inputs[d].get("value");
},this);var c={params:JSON.encode({id:this.id,file:a})},b=Object.merge(Object.clone(this.fieldsAjax.options.data),c);this.fieldsAjax.send({data:b});},updateTitle:function(a){this.update(a,"title");
},updateSlug:function(a){this.update(a,"slug");},updateDescription:function(a){this.update(a,"description");},updateRequest:function(a){this.element.getElement(".statusbar .editfile-loader").setStyle("display","inline-block");
},updateSuccess:function(a){if(!JSON.validate(a)){return this.popup({title:"File Settings - Invalid Response",message:'<p class="error-intro">The response from the server had an invalid JSON string while trying to save Image Data. Following is the reply.</p>'+a});
}a=JSON.decode(a);if(a.status!="success"){return this.popup({title:"File Settings - Error",message:a.message});}var b=a.payload.file;["title","description"].each(function(c){var d=this.current.getElement(".image-"+c);
if(d){d.set("text",b[c]);}if(c=="description"){this.current.retrieve("descScrollbar").update();}},this);this.slices=b.Slices;this.loadSlice(this.currentSlice,true);
this.element.getElement(".statusbar .editfile-loader").setStyle("display","none");this.element.getElement(".statusbar .editfile-save").setStyle("display","none");
return this;},newSlice:function(){var b=this.current.retrieve("file-data");RokGallery.editPanel.setOptions({imageSize:{width:b.xsize.toInt(),height:b.ysize.toInt()}});
var a=[];RokGallery.tags.getValues().forEach(function(c){a.push({tag:c});});b.Tags=a;RokGallery.editPanel.container.getElement(".image-status .navigation").setStyle("display","none");
RokGallery.editPanel.load(b);},editSlice:function(){var b=this.current.retrieve("file-data");RokGallery.editPanel.setOptions({imageSize:{width:b.xsize.toInt(),height:b.ysize.toInt()}});
var a=[];RokGallery.tags.getValues().forEach(function(c){a.push({tag:c});});b.Tags=a;this.slices[this.currentSlice].FileTags=a;RokGallery.editPanel.container.getElement(".image-status .navigation").setStyle("display","block");
RokGallery.editPanel.container.getElement(".image-status .slice-current-no").set("text",this.currentSlice+1);RokGallery.editPanel.container.getElement(".image-status .slice-total-no").set("text",this.slices.length);
RokGallery.editPanel.load(b,this.slices[this.currentSlice]);},shareSlice:function(){var d=this.slices[this.currentSlice],c=window.location.protocol+"//"+window.location.host,b=c+d.imageurl;
var e=d.title;var f='<img src="'+d.imageurl+'" width="300" height="180" alt="'+e+'" title="'+e+'" />';var a="";a+='<p><label class="share" for="share-link">Share image slice link</label>				 		<input id="share-link" type="text" class="share" readonly="readonly" value="'+b+'" /></p>';
a+='<p><label class="share" for="share-embed">Embed on your page</label>						<input id="share-embed" type="text" class="share" readonly="readonly" value="" /></p>';
window.Popup.setPopup({title:"Share Slice",message:a,buttons:{ok:{show:true,label:"close"}},"continue":function(){this.content.getElements("input").removeEvents();
this.close();}}).open();window.Popup.popup.getElement("#share-embed").set("value",f);window.Popup.content.getElements("input").addEvent("click",function(){this.select();
});},publishSliceRequest:function(){if(this.publishSliceAjax.isRunning()){return;}this.element.getElement(".slices-controls .slice-publish, .slices-controls .slice-unpublish").addClass("loader");
var a={model:"Slice",action:"update",params:JSON.encode({id:this.slices[this.currentSlice].id,slice:{published:!this.slices[this.currentSlice].published}})};
this.publishSliceAjax.send({data:a});},publishSliceResponse:function(a){this.element.getElement(".slices-controls .slice-publish, .slices-controls .slice-unpublish").removeClass("loader");
if(!JSON.validate(a)){return this.popup({title:"Slice Publish / Unpublish - Invalid Response",message:'<p class="error-intro">The response from the server had an invalid JSON string while trying to publish/unpublish the Slice. Following is the reply.</p>'+a});
}a=JSON.decode(a);if(a.status!="success"){return this.popup({title:"Slice Publish / Unpublish - Error",message:a.message});}this.slices[this.currentSlice]=a.payload.slice;
this.publishSlice();RokGallery.blocks.setPublishState(a.payload.slice.File.published);return this;},deleteSliceRequest:function(){if(this.deleteAjax.isRunning()){return;
}var a=this,b={model:"Slice",action:"delete",params:JSON.encode({id:this.slices[this.currentSlice].id})};window.Popup.setPopup({type:"warning",title:"Slice Deletion - Are you sure?",message:"<p>You are about to delete the current Slice <strong>"+this.slices[this.currentSlice].title+"</strong></p> 						  <p>This operation is irreversible, are you sure you want to continue?</p>",buttons:{ok:{show:true,label:"yes"},cancel:{show:true,label:"no"}},"continue":function(){a.deleteAjax.send({data:b});
this.close();}}).open();},deleteSliceResponse:function(a){if(!JSON.validate(a)){return this.popup({title:"Slice Deletion - Invalid Response",message:'<p class="error-intro">The response from the server had an invalid JSON string while trying to delete the Slice. Following is the reply.</p>'+a});
}a=JSON.decode(a);if(a.status!="success"){return this.popup({title:"Slice Deletion - Error",message:a.message});}this.deleteSlice();return this;},publishSlice:function(c,f){c=(c&&c.toInt())||this.currentSlice;
f=typeof f=="undefined"?this.slices[c].published:f;var d=this.element.getElement(".slices-controls .slice-publish, .slices-controls .slice-unpublish"),b=f,e=b?"unpublish":"publish",a=b?"publish":"unpublish";
d.set("title",e).removeClass("slice-"+a).addClass("slice-"+e);},deleteSlice:function(a){a=(a&&a.toInt())||this.currentSlice;this.slices.splice(a,1);this.currentSlice=this.slices.length-1;
if(this.currentSlice<0){this.currentSlice=this.currentSlice.length;}this.loadSlice(this.currentSlice,true);},publishFile:function(){RokGallery.blocks.publish(this.current);
},removeFile:function(){RokGallery.blocks.remove(this.current);},attachArrows:function(){var a=0;Object.forEach(this.bounds.arrows,function(c,b){this.arrows[a].addEvent("click",c);
a++;},this);},detachArrows:function(){var a=0;Object.forEach(this.bounds.arrows,function(b){this.arrows[a].removeEvent("click",b);a++;},this);},previousSlice:function(){if(this.slices.length==1){return;
}var a=this.currentSlice;this.currentSlice=(!~(a-1))?this.slices.length-1:a-1;this.loadSlice(this.currentSlice);},nextSlice:function(){if(this.slices.length==1){return;
}var a=this.currentSlice;this.currentSlice=(a+1>this.slices.length-1)?0:a+1;this.loadSlice(this.currentSlice);},popup:function(a){var b={type:"warning",title:"Error",message:"",buttons:{ok:{show:false},cancel:{show:true,label:"close"}}};
window.Popup.setPopup(Object.merge(b,a)).open();}});})());