this.wc=this.wc||{},this.wc.blocks=this.wc.blocks||{},this.wc.blocks["all-products"]=function(e){function t(t){for(var n,a,l=t[0],i=t[1],u=t[2],b=0,p=[];b<l.length;b++)a=l[b],Object.prototype.hasOwnProperty.call(c,a)&&c[a]&&p.push(c[a][0]),c[a]=0;for(n in i)Object.prototype.hasOwnProperty.call(i,n)&&(e[n]=i[n]);for(s&&s(t);p.length;)p.shift()();return o.push.apply(o,u||[]),r()}function r(){for(var e,t=0;t<o.length;t++){for(var r=o[t],n=!0,l=1;l<r.length;l++){var i=r[l];0!==c[i]&&(n=!1)}n&&(o.splice(t--,1),e=a(a.s=r[0]))}return e}var n={},c={4:0},o=[];function a(t){if(n[t])return n[t].exports;var r=n[t]={i:t,l:!1,exports:{}};return e[t].call(r.exports,r,r.exports,a),r.l=!0,r.exports}a.m=e,a.c=n,a.d=function(e,t,r){a.o(e,t)||Object.defineProperty(e,t,{enumerable:!0,get:r})},a.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},a.t=function(e,t){if(1&t&&(e=a(e)),8&t)return e;if(4&t&&"object"==typeof e&&e&&e.__esModule)return e;var r=Object.create(null);if(a.r(r),Object.defineProperty(r,"default",{enumerable:!0,value:e}),2&t&&"string"!=typeof e)for(var n in e)a.d(r,n,function(t){return e[t]}.bind(null,n));return r},a.n=function(e){var t=e&&e.__esModule?function(){return e.default}:function(){return e};return a.d(t,"a",t),t},a.o=function(e,t){return Object.prototype.hasOwnProperty.call(e,t)},a.p="";var l=window.webpackWcBlocksJsonp=window.webpackWcBlocksJsonp||[],i=l.push.bind(l);l.push=t,l=l.slice();for(var u=0;u<l.length;u++)t(l[u]);var s=i;return o.push([639,2,1,0]),r()}({0:function(e,t){!function(){e.exports=this.wp.element}()},1:function(e,t){!function(){e.exports=this.wp.i18n}()},10:function(e,t){!function(){e.exports=this.React}()},108:function(e,t,r){"use strict";r.d(t,"a",(function(){return l}));var n=r(35),c=r(34),o=r(0),a=r(38),l=function(e){var t=e.namespace,r=e.resourceName,l=e.resourceValues,i=void 0===l?[]:l,u=e.query,s=void 0===u?{}:u,b=e.shouldSelect,p=void 0===b||b;if(!t||!r)throw new Error("The options object must have valid values for the namespace and the resource properties.");var d=Object(o.useRef)({results:[],isLoading:!0}),g=Object(a.a)(s),m=Object(a.a)(i),f=Object(c.useSelect)((function(e){if(!p)return null;var c=e(n.COLLECTIONS_STORE_KEY),o=[t,r,g,m];return{results:c.getCollection.apply(c,o),isLoading:!c.hasFinishedResolution("getCollection",o)}}),[t,r,m,g,p]);return null!==f&&(d.current=f),d.current}},17:function(e,t,r){"use strict";r.d(t,"e",(function(){return c})),r.d(t,"r",(function(){return o})),r.d(t,"k",(function(){return a})),r.d(t,"m",(function(){return l})),r.d(t,"b",(function(){return i})),r.d(t,"l",(function(){return u})),r.d(t,"o",(function(){return s})),r.d(t,"d",(function(){return b})),r.d(t,"n",(function(){return p})),r.d(t,"c",(function(){return d})),r.d(t,"p",(function(){return g})),r.d(t,"i",(function(){return m})),r.d(t,"j",(function(){return f})),r.d(t,"f",(function(){return O})),r.d(t,"g",(function(){return j})),r.d(t,"h",(function(){return v})),r.d(t,"q",(function(){return w})),r.d(t,"a",(function(){return _})),r.d(t,"s",(function(){return h}));var n=r(4),c=Object(n.getSetting)("enableReviewRating",!0),o=Object(n.getSetting)("showAvatars",!0),a=Object(n.getSetting)("max_columns",6),l=Object(n.getSetting)("min_columns",1),i=Object(n.getSetting)("default_columns",3),u=Object(n.getSetting)("max_rows",6),s=Object(n.getSetting)("min_rows",1),b=Object(n.getSetting)("default_rows",2),p=Object(n.getSetting)("min_height",500),d=Object(n.getSetting)("default_height",500),g=Object(n.getSetting)("placeholderImgSrc",""),m=(Object(n.getSetting)("thumbnail_size",300),Object(n.getSetting)("isLargeCatalog")),f=Object(n.getSetting)("limitTags"),O=Object(n.getSetting)("hasProducts",!0),j=Object(n.getSetting)("hasTags",!0),v=Object(n.getSetting)("homeUrl",""),w=Object(n.getSetting)("productCount",0),_=Object(n.getSetting)("attributes",[]),h=Object(n.getSetting)("wcBlocksAssetUrl","")},21:function(e,t){!function(){e.exports=this.wp.compose}()},25:function(e,t){!function(){e.exports=this.wp.blocks}()},3:function(e,t){!function(){e.exports=this.wp.components}()},338:function(e,t,r){"use strict";var n=r(0),c=r(3);t.a=function(){return Object(n.createElement)(c.Icon,{icon:Object(n.createElement)("svg",{xmlns:"http://www.w3.org/2000/svg",width:"24",height:"24",viewBox:"0 0 24 24"},Object(n.createElement)("mask",{id:"external-mask",width:"24",height:"24",x:"0",y:"0",maskUnits:"userSpaceOnUse"},Object(n.createElement)("path",{fill:"#fff",d:"M6.3431 6.3431v1.994l7.8984.0072-8.6055 8.6054 1.4142 1.4143 8.6055-8.6055.0071 7.8984h1.994V6.3431H6.3431z"})),Object(n.createElement)("g",{mask:"url(#external-mask)"},Object(n.createElement)("path",{d:"M0 0h24v24H0z"})))})}},34:function(e,t){!function(){e.exports=this.wp.data}()},344:function(e,t){!function(){e.exports=this.wc.wcBlocksRegistry}()},35:function(e,t){!function(){e.exports=this.wc.wcBlocksData}()},38:function(e,t,r){"use strict";r.d(t,"a",(function(){return a}));var n=r(0),c=r(42),o=r.n(c),a=function(e){var t=Object(n.useRef)();return o()(e,t.current)||(t.current=e),t.current}},4:function(e,t){!function(){e.exports=this.wc.wcSettings}()},42:function(e,t){!function(){e.exports=this.wp.isShallowEqual}()},45:function(e,t,r){"use strict";var n=r(0),c=r(1),o=r(5),a=(r(2),r(3)),l=r(17);t.a=function(e){var t=e.columns,r=e.rows,i=e.setAttributes,u=e.alignButtons;return Object(n.createElement)(n.Fragment,null,Object(n.createElement)(a.RangeControl,{label:Object(c.__)("Columns","woo-gutenberg-products-block"),value:t,onChange:function(e){var t=Object(o.clamp)(e,l.m,l.k);i({columns:Object(o.isNaN)(t)?"":t})},min:l.m,max:l.k}),Object(n.createElement)(a.RangeControl,{label:Object(c.__)("Rows","woo-gutenberg-products-block"),value:r,onChange:function(e){var t=Object(o.clamp)(e,l.o,l.l);i({rows:Object(o.isNaN)(t)?"":t})},min:l.o,max:l.l}),Object(n.createElement)(a.ToggleControl,{label:Object(c.__)("Align Buttons","woo-gutenberg-products-block"),help:u?Object(c.__)("Buttons are aligned vertically.","woo-gutenberg-products-block"):Object(c.__)("Buttons follow content.","woo-gutenberg-products-block"),checked:u,onChange:function(){return i({alignButtons:!u})}}))}},47:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(0),c=Object(n.createContext)("page"),o=function(){return Object(n.useContext)(c)};c.Provider},49:function(e,t){!function(){e.exports=this.wp.blockEditor}()},5:function(e,t){!function(){e.exports=this.lodash}()},52:function(e,t,r){"use strict";var n=r(7),c=r.n(n),o=r(0),a=(r(2),r(10)),l=r(6),i=r.n(l);function u(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var s=function(e){var t,r=e.label,n=e.screenReaderLabel,l=e.wrapperElement,s=e.wrapperProps;return!r&&n?(t=l||"span",s=function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?u(Object(r),!0).forEach((function(t){c()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):u(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},s,{className:i()(s.className,"screen-reader-text")}),Object(o.createElement)(t,s,n)):(t=l||a.Fragment,r&&n&&r!==n?Object(o.createElement)(t,s,Object(o.createElement)("span",{"aria-hidden":"true"},r),Object(o.createElement)("span",{className:"screen-reader-text"},n)):Object(o.createElement)(t,s,r))};s.defaultProps={wrapperProps:{}},t.a=s},614:function(e,t,r){var n=r(615);"string"==typeof n&&(n=[[e.i,n,""]]);var c={insert:"head",singleton:!1};r(30)(n,c);n.locals&&(e.exports=n.locals)},615:function(e,t,r){},639:function(e,t,r){"use strict";r.r(t);var n=r(11),c=r.n(n),o=r(7),a=r.n(o),l=r(0),i=r(1),u=r(49),s=r(25),b=r(29),p=r.n(b),d=r(24),g=r.n(d),m=r(13),f=r.n(m),O=r(14),j=r.n(O),v=r(15),w=r.n(v),_=r(12),h=r.n(_),y=r(16),k=r.n(y),E=r(34),P=r(3),S=r(21),N=(r(2),r(45)),C=r(17),x=r(84),T=r.n(x),B=function(e,t,r){if("object"!==T()(r))throw new Error("".concat(e," expects an object for its context value"));var n=[];for(var c in t)t[c].required&&void 0===r[c]?n.push("The ".concat(c," is required and is not present.")):void 0!==r[c]&&T()(r[c])!==t[c].type&&n.push("The ".concat(c," must be of ").concat(t[c].type," and instead was ").concat(T()(r[c])));if(n.length>0)throw new Error("There was a problem with the value passed in on ".concat(e,":\n ").concat(n.join("\n")))},R={parentName:{required:!0,type:"string"}},I=Object(l.createContext)({parentName:null}),D=function(e){var t=e.value,r=e.children;return Object(l.useEffect)((function(){B("InnerBlockConfigurationProvider",R,t)}),[t]),Object(l.createElement)(I.Provider,{value:t},r)},L={layoutStyleClassPrefix:{required:!0,type:"string"}},A=Object(l.createContext)({layoutStyleClassPrefix:""}),M=function(){return Object(l.useContext)(A)},F=function(e){var t=e.value,r=e.children;return Object(l.useEffect)((function(){B("ProductLayoutContextProvider",L,t)}),[t]),Object(l.createElement)(A.Provider,{value:t},r)},V=r(6),q=r.n(V),Q=r(4),Y=r(338),U=function(e,t){var r=t.className,n=t.contentVisibility;return q()(e,r,{"has-image":n.image,"has-title":n.title,"has-rating":n.rating,"has-price":n.price,"has-button":n.button})},K=function(e,t){return Object(l.createElement)(P.Placeholder,{className:"wc-block-products",icon:t,label:e},Object(l.createElement)("p",null,Object(i.__)("You haven't published any products to list here yet.","woo-gutenberg-products-block")),Object(l.createElement)(P.Button,{className:"wc-block-products__add_product_button",isDefault:!0,isLarge:!0,href:Q.adminUrl+"post-new.php?post_type=product"},Object(i.__)("Add new product","woo-gutenberg-products-block")+" ",Object(l.createElement)(Y.a,null)),Object(l.createElement)(P.Button,{className:"wc-block-products__read_more_button",isTertiary:!0,href:"https://docs.woocommerce.com/document/managing-products/"},Object(i.__)("Learn more","woo-gutenberg-products-block")))},H=function(e,t){return Object(l.createElement)(P.Placeholder,{className:"wc-block-products",icon:t,label:e},Object(i.__)("The content for this block is hidden due to block settings.","woo-gutenberg-products-block"))},z=r(344),W=r(140),J=function(e){var t=e.className,r=e.product,n=M().layoutStyleClassPrefix,o=r.prices||{},a={displayType:"text",thousandSeparator:o.thousand_separator,decimalSeparator:o.decimal_separator,decimalScale:o.decimals,prefix:o.price_prefix,suffix:o.price_suffix};return o.price_range&&o.price_range.min_amount&&o.price_range.max_amount?Object(l.createElement)("div",{className:q()(t,"".concat(n,"__product-price"))},Object(l.createElement)("span",{className:"".concat(n,"__product-price__value")},Object(l.createElement)(W.a,c()({value:o.price_range.min_amount},a))," — ",Object(l.createElement)(W.a,c()({value:o.price_range.max_amount},a)))):Object(l.createElement)("div",{className:q()(t,"".concat(n,"__product-price"))},o.regular_price!==o.price&&Object(l.createElement)("del",{className:"".concat(n,"__product-price__regular")},Object(l.createElement)(W.a,c()({value:o.regular_price},a))),Object(l.createElement)("span",{className:"".concat(n,"__product-price__value")},Object(l.createElement)(W.a,c()({value:o.price},a))))},G=function(e){var t=e.className,r=e.product,n=e.align,c=M().layoutStyleClassPrefix,o="string"==typeof n?"".concat(c,"__product-onsale--align").concat(n):"";return r&&r.on_sale?Object(l.createElement)("div",{className:q()(t,o,"".concat(c,"__product-onsale"))},Object(i.__)("Sale","woo-gutenberg-products-block")):null},X=function(e){var t=e.product,r=e.saleBadgeAlign;return e.shouldRender?Object(l.createElement)(G,{product:t,align:r}):null},Z=function(e){var t=e.layoutPrefix,r=e.loaded,n=e.image,c=e.onLoad,o=q()("".concat(t,"__product-image__image"),a()({},"".concat(t,"__product-image__image_placeholder"),!r&&!n)),i=n||{},u=i.thumbnail,s=i.srcset,b=i.sizes,p=i.alt;return Object(l.createElement)(l.Fragment,null,n&&Object(l.createElement)("img",{className:o,src:u,srcSet:s,sizes:b,alt:p,onLoad:c,hidden:!r}),!r&&Object(l.createElement)("img",{className:o,src:C.p,alt:""}))},$=function(e){var t=e.className,r=e.product,n=e.productLink,c=void 0===n||n,o=e.showSaleBadge,a=void 0===o||o,i=e.saleBadgeAlign,u=void 0===i?"right":i,s=Object(l.useState)(!1),b=g()(s,2),p=b[0],d=b[1],m=M().layoutStyleClassPrefix,f=r.images&&r.images.length?r.images[0]:null,O=Object(l.createElement)(l.Fragment,null,Object(l.createElement)(X,{product:r,saleBadgeAlign:u,shouldRender:a}),Object(l.createElement)(Z,{layoutPrefix:m,loaded:p,image:f,onLoad:function(){return d(!0)}}));return Object(l.createElement)("div",{className:q()(t,"".concat(m,"__product-image"))},c?Object(l.createElement)("a",{href:r.permalink,rel:"nofollow"},O):{renderedSalesAndImage:O})},ee=function(e){var t=e.className,r=e.product,n=e.headingLevel,c=void 0===n?2:n,o=e.productLink,a=void 0===o||o,i=M().layoutStyleClassPrefix;if(!r.name)return null;var u=r.name,s="h".concat(c);return Object(l.createElement)(s,{className:q()(t,"".concat(i,"__product-title"))},a?Object(l.createElement)("a",{href:r.permalink,rel:"nofollow"},u):u)},te=function(e){var t=e.className,r=e.product,n=parseFloat(r.average_rating),c=M().layoutStyleClassPrefix;if(!Number.isFinite(n)||0===n)return null;var o={width:n/5*100+"%"};return Object(l.createElement)("div",{className:q()(t,"".concat(c,"__product-rating"))},Object(l.createElement)("div",{className:"".concat(c,"__product-rating__stars"),role:"img"},Object(l.createElement)("span",{style:o},Object(i.sprintf)(Object(i.__)("Rated %d out of 5","woo-gutenberg-products-block"),n))))},re=r(5),ne=r(108),ce=r(35),oe=window.Event||{},ae=function(e){var t=e.product,r=e.className,n=t.id,c=t.permalink,o=t.add_to_cart,a=t.has_options,u=t.is_purchasable,s=t.is_in_stock,b=function(e){var t=Object(ne.a)({namespace:"/wc/store",resourceName:"cart/items"}),r=t.results,n=t.isLoading,c=Object(l.useRef)(null),o=Object(E.useDispatch)(ce.COLLECTIONS_STORE_KEY).__experimentalPersistItemToCollection,a=Object(l.useMemo)((function(){var t=Object(re.find)(r,{id:e});return t?t.quantity:0}),[r,e]),i=Object(l.useState)(!1),u=g()(i,2),s=u[0],b=u[1],p=Object(l.useCallback)((function(){b(!0);var t=r.filter((function(t){return t.id!==e}));o("/wc/store","cart/items",t,{id:e,quantity:1})}),[e,r]);return Object(l.useEffect)((function(){c.current!==r&&(s&&b(!1),c.current=r)}),[r,s]),{cartQuantity:a,addingToCart:s,cartIsLoading:n,addToCart:p}}(n),p=b.cartQuantity,d=b.addingToCart,m=b.cartIsLoading,f=b.addToCart,O=M().layoutStyleClassPrefix,j=p>0,v=Object(l.useRef)(!0),w=function(){return Number.isFinite(p)&&j?Object(i.sprintf)(Object(i.__)("%d in cart","woo-gutenberg-products-block"),p):o.text};Object(l.useEffect)((function(){if(v.current)v.current=!1;else if(0!==Object.entries(oe).length){var e=new oe("wc_fragment_refresh",{bubbles:!0,cancelable:!0});document.body.dispatchEvent(e)}else{var t=document.createEvent("Event");t.initEvent("wc_fragment_refresh",!0,!0),document.body.dispatchEvent(t)}}),[p]);var _=q()(r,"".concat(O,"__product-add-to-cart"),"wp-block-button"),h=q()("wp-block-button__link","add_to_cart_button",{loading:d,added:j});if(0===Object.keys(t).length||m)return Object(l.createElement)("div",{className:_},Object(l.createElement)("button",{className:h,disabled:!0}));var y=!a&&u&&s;return Object(l.createElement)("div",{className:_},y?Object(l.createElement)("button",{onClick:f,"aria-label":o.description,className:h,disabled:d},w()):Object(l.createElement)("a",{href:c,"aria-label":o.description,className:h,rel:"nofollow"},w()))},le=function(e){var t=e.className,r=e.product,n=M().layoutStyleClassPrefix;return r.description?Object(l.createElement)("div",{className:q()(t,"".concat(n,"__product-summary")),dangerouslySetInnerHTML:{__html:r.description}}):null};function ie(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function ue(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?ie(Object(r),!0).forEach((function(t){a()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):ie(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var se=function(e){return ue({"woocommerce/product-price":J,"woocommerce/product-image":$,"woocommerce/product-title":ee,"woocommerce/product-rating":te,"woocommerce/product-button":ae,"woocommerce/product-summary":le,"woocommerce/product-sale-badge":G},Object(z.getRegisteredInnerBlocks)(e))},be=[["woocommerce/product-image"],["woocommerce/product-title"],["woocommerce/product-price"],["woocommerce/product-rating"],["woocommerce/product-button"]],pe=function e(t){return t&&0!==t.length?t.map((function(t){return[t.name,ue({},t.attributes,{product:void 0,children:t.innerBlocks.length>0?e(t.innerBlocks):[]})]})):[]};r(614);function de(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function ge(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?de(Object(r),!0).forEach((function(t){a()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):de(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var me=function(e,t){var r=e.contentVisibility;return Object(l.createElement)(P.ToggleControl,{label:Object(i.__)("Show Sorting Dropdown","woo-gutenberg-products-block"),checked:r.orderBy,onChange:function(){return t({contentVisibility:ge({},r,{orderBy:!r.orderBy})})}})},fe=function(e,t){return Object(l.createElement)(P.SelectControl,{label:Object(i.__)("Order Products By","woo-gutenberg-products-block"),value:e.orderby,options:[{label:Object(i.__)("Newness - newest first","woo-gutenberg-products-block"),value:"date"},{label:Object(i.__)("Price - low to high","woo-gutenberg-products-block"),value:"price"},{label:Object(i.__)("Price - high to low","woo-gutenberg-products-block"),value:"price-desc"},{label:Object(i.__)("Rating - highest first","woo-gutenberg-products-block"),value:"rating"},{label:Object(i.__)("Sales - most first","woo-gutenberg-products-block"),value:"popularity"},{label:Object(i.__)("Menu Order","woo-gutenberg-products-block"),value:"menu_order"}],onChange:function(e){return t({orderby:e})}})},Oe=r(18),je=r.n(Oe),ve=r(10),we=r(345),_e=r.n(we),he=r(52),ye=(r(621),function(e){var t=e.currentPage,r=e.displayFirstAndLastPages,n=e.displayNextAndPreviousArrows,c=e.pagesToDisplay,o=e.onPageChange,a=e.totalPages,u=function(e,t,r){if(r<=2)return{minIndex:null,maxIndex:null};var n=e-1,c=Math.max(Math.floor(t-n/2),2),o=Math.min(Math.ceil(t+(n-(t-c))),r-1);return{minIndex:Math.max(Math.floor(t-(n-(o-t))),2),maxIndex:o}}(c,t,a),s=u.minIndex,b=u.maxIndex,p=r&&Boolean(1!==s),d=r&&Boolean(b!==a),g=r&&Boolean(s>3),m=r&&Boolean(b<a-2);p&&3===s&&(s-=1),d&&b===a-2&&(b+=1);var f=[];if(s&&b)for(var O=s;O<=b;O++)f.push(O);return Object(l.createElement)("div",{className:"wc-block-pagination"},Object(l.createElement)(he.a,{screenReaderLabel:Object(i.__)("Navigate to another page","woo-gutenberg-products-block")}),n&&Object(l.createElement)("button",{className:"wc-block-pagination-page",onClick:function(){return o(t-1)},title:Object(i.__)("Previous page","woo-gutenberg-products-block"),disabled:t<=1},Object(l.createElement)(he.a,{label:"<",screenReaderLabel:Object(i.__)("Previous page","woo-gutenberg-products-block")})),p&&Object(l.createElement)("button",{className:q()("wc-block-pagination-page",{"wc-block-pagination-page--active":1===t}),onClick:function(){return o(1)},disabled:1===t},"1"),g&&Object(l.createElement)("span",{className:"wc-block-pagination-ellipsis","aria-hidden":"true"},Object(i.__)("…","woo-gutenberg-products-block")),f.map((function(e){return Object(l.createElement)("button",{key:e,className:q()("wc-block-pagination-page",{"wc-block-pagination-page--active":t===e}),onClick:t===e?null:function(){return o(e)},disabled:t===e},e)})),m&&Object(l.createElement)("span",{className:"wc-block-pagination-ellipsis","aria-hidden":"true"},Object(i.__)("…","woo-gutenberg-products-block")),d&&Object(l.createElement)("button",{className:q()("wc-block-pagination-page",{"wc-block-pagination-page--active":t===a}),onClick:function(){return o(a)},disabled:t===a},a),n&&Object(l.createElement)("button",{className:"wc-block-pagination-page",onClick:function(){return o(t+1)},title:Object(i.__)("Next page","woo-gutenberg-products-block"),disabled:t>=a},Object(l.createElement)(he.a,{label:">",screenReaderLabel:Object(i.__)("Next page","woo-gutenberg-products-block")})))});ye.defaultProps={displayFirstAndLastPages:!0,displayNextAndPreviousArrows:!0,pagesToDisplay:3};var ke=ye,Ee=r(83),Pe=(r(619),function(e){var t=e.defaultValue,r=e.onChange,n=e.readOnly,c=e.value;return Object(l.createElement)(Ee.a,{className:"wc-block-product-sort-select",defaultValue:t,name:"orderby",onChange:r,options:[{key:"menu_order",label:Object(i.__)("Default sorting","woo-gutenberg-products-block")},{key:"popularity",label:Object(i.__)("Popularity","woo-gutenberg-products-block")},{key:"rating",label:Object(i.__)("Average rating","woo-gutenberg-products-block")},{key:"date",label:Object(i.__)("Latest","woo-gutenberg-products-block")},{key:"price",label:Object(i.__)("Price: low to high","woo-gutenberg-products-block")},{key:"price-desc",label:Object(i.__)("Price: high to low","woo-gutenberg-products-block")}],readOnly:n,screenReaderLabel:Object(i.__)("Order products by","woo-gutenberg-products-block"),value:c})}),Se=r(79),Ne=Object(Se.a)((function(e){var t=e.product,r=e.attributes,n=e.componentId,o=r.layoutConfig,a=Object(l.useContext)(I).parentName,i=M().layoutStyleClassPrefix,u=!Object.keys(t).length>0,s=q()("".concat(i,"__product"),{"is-loading":u});return Object(l.createElement)("li",{className:s,"aria-hidden":u},function e(t,r,n,o){if(n){var a=se(t);return n.map((function(n,i){var u=g()(n,2),s=u[0],b=u[1],p=void 0===b?{}:b,d=[];p.children&&p.children.length>0&&(d=e(t,r,p.children,o));var m=a[s];if(!m)return null;var f=r.id||0,O=["layout",s,i,o,f];return Object(l.createElement)(m,c()({key:O.join("_")},p,{children:d,product:r}))}))}}(a,t,o,n))})),Ce=r(90),xe=r(38);function Te(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}function Be(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Te(Object(r),!0).forEach((function(t){a()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Te(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}var Re=function(e){var t={namespace:"/wc/store",resourceName:"products"},r=Object(ne.a)(Be({},t,{query:e})),n=r.results,c=r.isLoading;return{products:n,totalProducts:function(e,t){var r=t.namespace,n=t.resourceName,c=t.resourceValues,o=void 0===c?[]:c,a=t.query,l=void 0===a?{}:a;if(!r||!n)throw new Error("The options object must have valid values for the namespace and the resource name properties.");var i=Object(xe.a)(l),u=Object(xe.a)(o),s=Object(E.useSelect)((function(t){var c=t(ce.COLLECTIONS_STORE_KEY),o=[e,r,n,i,u];return{value:c.getCollectionHeader.apply(c,o),isLoading:c.hasFinishedResolution("getCollectionHeader",o)}}),[e,r,n,u,i]),b=s.value,p=s.isLoading;return{value:b,isLoading:void 0===p||p}}("x-wp-total",Be({},t,{query:e})).value,productsLoading:c}},Ie=(r(623),function(e){var t=function(t){function r(){var e;return f()(this,r),e=j()(this,w()(r).call(this)),a()(h()(e),"scrollToTopIfNeeded",(function(){var t=e.scrollPointRef.current.getBoundingClientRect().bottom;t>=0&&t<=window.innerHeight||e.scrollPointRef.current.scrollIntoView()})),a()(h()(e),"moveFocusToTop",(function(t){var r=e.scrollPointRef.current.parentElement.querySelectorAll(t);r.length&&r[0].focus()})),a()(h()(e),"scrollToTop",(function(t){window&&Number.isFinite(window.innerHeight)&&(e.scrollToTopIfNeeded(),t&&t.focusableSelector&&e.moveFocusToTop(t.focusableSelector))})),e.scrollPointRef=Object(ve.createRef)(),e}return k()(r,t),je()(r,[{key:"render",value:function(){return Object(l.createElement)(ve.Fragment,null,Object(l.createElement)("div",{className:"with-scroll-to-top__scroll-point",ref:this.scrollPointRef,"aria-hidden":!0}),Object(l.createElement)(e,c()({},this.props,{scrollToTop:this.scrollToTop})))}}]),r}(ve.Component);return t.displayName="withScrollToTop",t}),De=(r(617),function(){var e=M().layoutStyleClassPrefix;return Object(l.createElement)("div",{className:"".concat(e,"__no-products")},Object(l.createElement)("img",{src:C.s+"img/no-products.svg",alt:Object(i.__)("No products","woo-gutenberg-products-block"),className:"".concat(e,"__no-products-image")}),Object(l.createElement)("strong",{className:"".concat(e,"__no-products-title")},Object(i.__)("No products","woo-gutenberg-products-block")),Object(l.createElement)("p",{className:"".concat(e,"__no-products-description")},Object(i.__)("There are currently no products available to display.","woo-gutenberg-products-block")))}),Le=function(e){var t=e.resetCallback,r=void 0===t?function(){}:t,n=M().layoutStyleClassPrefix;return Object(l.createElement)("div",{className:"".concat(n,"__no-products")},Object(l.createElement)("img",{src:C.s+"img/no-matching-products.svg",alt:Object(i.__)("No products","woo-gutenberg-products-block"),className:"".concat(n,"__no-products-image")}),Object(l.createElement)("strong",{className:"".concat(n,"__no-products-title")},Object(i.__)("No products found","woo-gutenberg-products-block")),Object(l.createElement)("p",{className:"".concat(n,"__no-products-description")},Object(i.__)("We were unable to find any results based on your search.","woo-gutenberg-products-block")),Object(l.createElement)("button",{onClick:r},Object(i.__)("Reset Search","woo-gutenberg-products-block")))};function Ae(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var Me=function(e){var t=e.sortValue,r=e.currentPage,n=e.attributes,c=n.columns,o=n.rows;return function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Ae(Object(r),!0).forEach((function(t){a()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Ae(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},function(e){switch(e){case"menu_order":case"popularity":case"rating":case"date":case"price":return{orderby:e,order:"asc"};case"price-desc":return{orderby:"price",order:"desc"}}}(t),{per_page:c*o,page:r})},Fe=Ie((function(e){var t,r,n,c=e.attributes,o=e.currentPage,a=e.onPageChange,i=e.onSortChange,u=e.sortValue,s=e.scrollToTop,b=Object(Ce.c)(Me({attributes:c,sortValue:u,currentPage:o})),p=g()(b,1)[0],d=Re(p),m=d.products,f=d.productsLoading,O=parseInt(d.totalProducts),j=M().layoutStyleClassPrefix,v=function(e){e.order,e.orderby,e.page,e.per_page;return _e()(e,["order","orderby","page","per_page"])}(p),w=Object(Ce.b)("attributes",[]),_=g()(w,2),h=_[0],y=_[1],k=Object(Ce.b)("min_price"),E=g()(k,2),P=E[0],S=E[1],N=Object(Ce.b)("max_price"),C=g()(N,2),x=C[0],B=C[1],R=(t={totalQuery:v,totalProducts:O},r=function(e){var t=e.totalQuery,r=e.totalProducts,n=arguments.length>1&&void 0!==arguments[1]?arguments[1]:{},c=n.totalQuery;return!Object(re.isEqual)(t,c)&&Number.isFinite(r)},n=Object(ve.useRef)(),Object(ve.useEffect)((function(){n.current===t||r&&!r(t,n.current)||(n.current=t)}),[t,n.current]),n.current),I="object"===T()(R)&&Object(re.isEqual)(v,R.totalQuery);Object(l.useEffect)((function(){I||a(1)}),[p]);var D,L,A,F,V,Q=c.contentVisibility,Y=c.columns*c.rows,U=!Number.isFinite(O)&&I?Math.ceil(R.totalProducts/Y):Math.ceil(O/Y),K=m.length?m:Array.from({length:Y}),H=0!==m.length||f,z=h.length>0||Number.isFinite(P)||Number.isFinite(x);return Object(l.createElement)("div",{className:(D=c.columns,L=c.rows,A=c.alignButtons,F=c.align,V=void 0!==F?"align"+F:"",q()(j,V,"has-"+D+"-columns",{"has-multiple-rows":L>1,"has-aligned-buttons":A}))},Q.orderBy&&H&&Object(l.createElement)(Pe,{onChange:i,value:u}),!H&&z&&Object(l.createElement)(Le,{resetCallback:function(){y([]),S(null),B(null)}}),!H&&!z&&Object(l.createElement)(De,null),H&&Object(l.createElement)("ul",{className:"".concat(j,"__products")},K.map((function(){var e=arguments.length>0&&void 0!==arguments[0]?arguments[0]:{},t=arguments.length>1?arguments[1]:void 0;return Object(l.createElement)(Ne,{key:e.id||t,attributes:c,product:e})}))),U>1&&Object(l.createElement)(ke,{currentPage:o,onPageChange:function(e){s({focusableSelector:"a, button"}),a(e)},totalPages:U}))})),Ve=function(e){var t=e.attributes,r=Object(l.useState)(1),n=g()(r,2),c=n[0],o=n[1],a=Object(l.useState)(t.orderby),i=g()(a,2),u=i[0],s=i[1];return Object(l.createElement)(Fe,{attributes:t,currentPage:c,onPageChange:function(e){o(e)},onSortChange:function(e){var t=e.target.value;s(t),o(1)},sortValue:u})},qe=r(94),Qe={layoutStyleClassPrefix:"wc-block-grid"},Ye={parentName:"woocommerce/all-products"},Ue=function(e){function t(){return f()(this,t),j()(this,w()(t).apply(this,arguments))}return k()(t,e),je()(t,[{key:"render",value:function(){var e=this.props,t=e.attributes,r=e.urlParameterSuffix;return t.isPreview?qe.a:Object(l.createElement)(D,{value:Ye},Object(l.createElement)(F,{value:Qe},Object(l.createElement)(Ve,{attributes:t,urlParameterSuffix:r})))}}]),t}(ve.Component),Ke={layoutStyleClassPrefix:"wc-block-grid"},He={parentName:"woocommerce/all-products"},ze=function(e){function t(){var e,r;f()(this,t);for(var n=arguments.length,c=new Array(n),o=0;o<n;o++)c[o]=arguments[o];return r=j()(this,(e=w()(t)).call.apply(e,[this].concat(c))),a()(h()(r),"state",{isEditing:!1,innerBlocks:[]}),a()(h()(r),"blockMap",se("woocommerce/all-products")),a()(h()(r),"componentDidMount",(function(){var e=r.props.block;r.setState({innerBlocks:e.innerBlocks})})),a()(h()(r),"getTitle",(function(){return Object(i.__)("All Products","woo-gutenberg-products-block")})),a()(h()(r),"getIcon",(function(){return Object(l.createElement)(p.a,{icon:"grid"})})),a()(h()(r),"togglePreview",(function(){var e=r.props.debouncedSpeak;r.setState({isEditing:!r.state.isEditing}),r.state.isEditing||e(Object(i.__)("Showing All Products block preview.","woo-gutenberg-products-block"))})),a()(h()(r),"getInspectorControls",(function(){var e=r.props,t=e.attributes,n=e.setAttributes,c=t.columns,o=t.rows,a=t.alignButtons;return Object(l.createElement)(u.InspectorControls,{key:"inspector"},Object(l.createElement)(P.PanelBody,{title:Object(i.__)("Layout Settings","woo-gutenberg-products-block"),initialOpen:!0},Object(l.createElement)(N.a,{columns:c,rows:o,alignButtons:a,setAttributes:n})),Object(l.createElement)(P.PanelBody,{title:Object(i.__)("Content Settings","woo-gutenberg-products-block")},me(t,n),fe(t,n)))})),a()(h()(r),"getBlockControls",(function(){var e=r.state.isEditing;return Object(l.createElement)(u.BlockControls,null,Object(l.createElement)(P.Toolbar,{controls:[{icon:"edit",title:Object(i.__)("Edit","woo-gutenberg-products-block"),onClick:function(){return r.togglePreview()},isActive:e}]}))})),a()(h()(r),"renderEditMode",(function(){var e={template:r.props.attributes.layoutConfig,templateLock:!1,allowedBlocks:Object.keys(r.blockMap)};return 0!==r.props.attributes.layoutConfig.length&&(e.renderAppender=!1),Object(l.createElement)(P.Placeholder,{icon:r.getIcon(),label:r.getTitle()},Object(i.__)("Display all products from your store as a grid.","woo-gutenberg-products-block"),Object(l.createElement)("div",{className:"wc-block-all-products-grid-item-template"},Object(l.createElement)(P.Tip,null,Object(i.__)("Edit the blocks inside the preview below to change the content displayed for each product within the product grid.","woo-gutenberg-products-block")),Object(l.createElement)("div",{className:"wc-block-grid has-1-columns"},Object(l.createElement)("ul",{className:"wc-block-grid__products"},Object(l.createElement)("li",{className:"wc-block-grid__product"},Object(l.createElement)(u.InnerBlocks,e)))),Object(l.createElement)("div",{className:"wc-block-all-products__actions"},Object(l.createElement)(P.Button,{className:"wc-block-all-products__done-button",isPrimary:!0,isLarge:!0,onClick:function(){var e=r.props,t=e.block;(0,e.setAttributes)({layoutConfig:pe(t.innerBlocks)}),r.setState({innerBlocks:t.innerBlocks}),r.togglePreview()}},Object(i.__)("Done","woo-gutenberg-products-block")),Object(l.createElement)(P.Button,{className:"wc-block-all-products__cancel-button",isTertiary:!0,onClick:function(){var e=r.props,t=e.block,n=e.replaceInnerBlocks,c=r.state.innerBlocks;n(t.clientId,c,!1),r.togglePreview()}},Object(i.__)("Cancel","woo-gutenberg-products-block")),Object(l.createElement)(P.IconButton,{className:"wc-block-all-products__reset-button",icon:Object(l.createElement)(p.a,{icon:"grid"}),label:Object(i.__)("Reset layout to default","woo-gutenberg-products-block"),onClick:function(){var e=r.props,t=e.block,n=e.replaceInnerBlocks,c=[];be.map((function(e){var t=g()(e,2),r=t[0],n=t[1];return c.push(Object(s.createBlock)(r,n)),!0})),n(t.clientId,c,!1),r.setState({innerBlocks:t.innerBlocks})}},Object(i.__)("Reset Layout","woo-gutenberg-products-block")))))})),a()(h()(r),"renderViewMode",(function(){var e=r.props.attributes,t=e.layoutConfig,n=t&&0!==t.length,c=r.getTitle(),o=r.getIcon();return n?Object(l.createElement)(P.Disabled,null,Object(l.createElement)(Ue,{attributes:e})):H(c,o)})),a()(h()(r),"render",(function(){var e=r.props.attributes,t=r.state.isEditing,n=r.getTitle(),c=r.getIcon();return C.f?Object(l.createElement)(D,{value:He},Object(l.createElement)(F,{value:Ke},Object(l.createElement)("div",{className:U("wc-block-all-products",e)},r.getBlockControls(),r.getInspectorControls(),t?r.renderEditMode():r.renderViewMode()))):K(n,c)})),r}return k()(t,e),t}(l.Component),We=Object(S.compose)(P.withSpokenMessages,Object(E.withSelect)((function(e,t){var r=t.clientId;return{block:(0,e("core/block-editor").getBlock)(r)}})),Object(E.withDispatch)((function(e){return{replaceInnerBlocks:e("core/block-editor").replaceInnerBlocks}})))(ze),Je={columns:{type:"number",default:C.b},rows:{type:"number",default:C.d},alignButtons:{type:"boolean",default:!1},contentVisibility:{type:"object",default:{orderBy:!0}},orderby:{type:"string",default:"date"},layoutConfig:{type:"array",default:be},isPreview:{type:"boolean",default:!1}};function Ge(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}Object(s.registerBlockType)("woocommerce/all-products",{title:Object(i.__)("All Products","woo-gutenberg-products-block"),icon:{src:Object(l.createElement)(p.a,{icon:"grid"}),foreground:"#96588a"},category:"woocommerce",keywords:[Object(i.__)("WooCommerce","woo-gutenberg-products-block")],description:Object(i.__)("Display all products from your store as a grid.","woo-gutenberg-products-block"),supports:{align:["wide","full"],html:!1},example:{attributes:{isPreview:!0}},attributes:function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?Ge(Object(r),!0).forEach((function(t){a()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):Ge(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},Je),edit:function(e){return Object(l.createElement)(We,e)},save:function(e){var t=e.attributes,r={"data-attributes":JSON.stringify(t)};return Object(l.createElement)("div",c()({className:U("wc-block-all-products",t)},r),Object(l.createElement)(u.InnerBlocks.Content,null))}})},79:function(e,t,r){"use strict";var n=r(11),c=r.n(n),o=r(13),a=r.n(o),l=r(18),i=r.n(l),u=r(14),s=r.n(u),b=r(15),p=r.n(b),d=r(12),g=r.n(d),m=r(16),f=r.n(m),O=r(7),j=r.n(O),v=r(0),w=r(10);t.a=function(e){var t=0,r=function(r){function n(){var e,r;a()(this,n);for(var c=arguments.length,o=new Array(c),l=0;l<c;l++)o[l]=arguments[l];return r=s()(this,(e=p()(n)).call.apply(e,[this].concat(o))),j()(g()(r),"instanceId",t++),r}return f()(n,r),i()(n,[{key:"render",value:function(){return Object(v.createElement)(e,c()({},this.props,{componentId:this.instanceId}))}}]),n}(w.Component);return r.displayName="withComponentId",r}},83:function(e,t,r){"use strict";var n=r(0),c=(r(2),r(6)),o=r.n(c),a=r(52),l=r(79);r(160);t.a=Object(l.a)((function(e){var t=e.className,r=e.componentId,c=e.defaultValue,l=e.label,i=e.onChange,u=e.options,s=e.screenReaderLabel,b=e.readOnly,p=e.value,d="wc-block-sort-select__select-".concat(r);return Object(n.createElement)("div",{className:o()("wc-block-sort-select",t)},Object(n.createElement)(a.a,{label:l,screenReaderLabel:s,wrapperElement:"label",wrapperProps:{className:"wc-block-sort-select__label",htmlFor:d}}),Object(n.createElement)("select",{id:d,className:"wc-block-sort-select__select",defaultValue:c,onChange:i,readOnly:b,value:p},u.map((function(e){return Object(n.createElement)("option",{key:e.key,value:e.key},e.label)}))))}))},90:function(e,t,r){"use strict";r.d(t,"a",(function(){return d})),r.d(t,"b",(function(){return g})),r.d(t,"c",(function(){return m}));var n=r(7),c=r.n(n),o=r(24),a=r.n(o),l=r(35),i=r(34),u=r(0),s=r(47),b=r(38);function p(e,t){var r=Object.keys(e);if(Object.getOwnPropertySymbols){var n=Object.getOwnPropertySymbols(e);t&&(n=n.filter((function(t){return Object.getOwnPropertyDescriptor(e,t).enumerable}))),r.push.apply(r,n)}return r}var d=function(e){var t=Object(s.a)();e=e||t;var r=Object(i.useSelect)((function(t){return t(l.QUERY_STATE_STORE_KEY).getValueForQueryContext(e,void 0)}),[e]),n=Object(i.useDispatch)(l.QUERY_STATE_STORE_KEY).setValueForQueryContext;return[r,Object(u.useCallback)((function(t){n(e,t)}),[e])]},g=function(e,t,r){var n=Object(s.a)();r=r||n;var c=Object(i.useSelect)((function(n){return n(l.QUERY_STATE_STORE_KEY).getValueForQueryKey(r,e,t)}),[r,e]),o=Object(i.useDispatch)(l.QUERY_STATE_STORE_KEY).setQueryValue;return[c,Object(u.useCallback)((function(t){o(r,e,t)}),[r,e])]},m=function(e,t){var r=Object(s.a)(),n=d(t=t||r),o=a()(n,2),l=o[0],i=o[1],g=Object(b.a)(e),m=Object(u.useRef)(!1);return Object(u.useEffect)((function(){i(function(e){for(var t=1;t<arguments.length;t++){var r=null!=arguments[t]?arguments[t]:{};t%2?p(Object(r),!0).forEach((function(t){c()(e,t,r[t])})):Object.getOwnPropertyDescriptors?Object.defineProperties(e,Object.getOwnPropertyDescriptors(r)):p(Object(r)).forEach((function(t){Object.defineProperty(e,t,Object.getOwnPropertyDescriptor(r,t))}))}return e}({},l,{},g)),m.current=!0}),[g]),m.current?[l,i]:[e,i]}},94:function(e,t,r){"use strict";r.d(t,"a",(function(){return o}));var n=r(0),c=r(17),o=Object(n.createElement)("img",{src:c.s+"img/grid.svg",alt:"Grid Preview",width:"230",height:"250",style:{width:"100%"}})}});