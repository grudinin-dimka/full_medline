export default {
	state: {
		axios: {
			urlApi: "/api/",
			urlCabinet: "/сapi/",
		},
	},
	getters: {
		urlApi(state) {
			return state.axios.urlApi;
		},

		urlCabinet(state) {
			return state.axios.urlCabinet;
		},
	},
};
