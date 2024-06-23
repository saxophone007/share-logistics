# share-logistics
###分享一个物流跟踪页面
分享一个vue的物流跟踪页面，是uniapp组件，微信小程序自带物流跟踪页面，直接引用即可，但是H5端没法直接引入，所以根据一个dcloud的插件进行接口对接，以及二次开发，这里只是做一个分享和记录。会把使用的接口，以及vue前端代码打包分享。  order.php里面是接口getOrderDeliveryData ，填入自己的APPCODE​，直接使用即可，前面的地址信息不会在接口中体现，所以直接从数据库中取出来。 logistics.vue 里面就是主要的页面了，有个方法getOrderDeliveryData就是调用的上面的接口。
