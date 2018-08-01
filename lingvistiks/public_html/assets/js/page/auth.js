
const auth = {
  template: '<div>auth</div>',
  data: function () {
    return $(".auth-comp");
  }
}

const restore = {
  template: '<div>restore</div>',
  data: function () {
    return $(".restore-comp");
  }
}

const routes = 	[
	{ path: ave_path + 'auth.php', component: auth },
	{ path: ave_path + 'restore.php', component: restore }
]

const router = new VueRouter({
	mode: 'history',
	routes
})

const app = new Vue({
	el: '#appAuth',
	delimiters: ['<%', '%>'],
	router
})
