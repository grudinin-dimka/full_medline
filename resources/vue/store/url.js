export default {
	state: {
		axios: {
			urlApi: "/api/",
			urlCabinet: "https://lk.medline.local/api/",
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
