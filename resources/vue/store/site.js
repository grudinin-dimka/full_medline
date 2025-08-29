export default {
	state: {},
	mutations: {
		/* Документ: Изменение заголовка */
		setDocumentTitle(state, title) {
			document.title = title;
		},

		/* Документ: Изменение метатега */
		setDocumentMeta(state, { name, value }) {
			let meta = document.querySelector(`meta[name="${name}"]`);

			// Наличие метатега
			if (meta) {
				let content = meta.getAttribute("content");

				switch (name) {
					case "description":
						content = value ? value : import.meta.env.VITE_MC_DESCRIPTION;
						break;
					case "keywords":
						if (content && value) {
							content = import.meta.env.VITE_MC_KEYWORDS + ", " + value;
						} else {
							content = value ? value : import.meta.env.VITE_MC_KEYWORDS;
						}
						break;
					default:
						content = value;
						break;
				}

				meta.setAttribute("content", content);
			} else {
				meta = document.createElement("meta");
				meta.setAttribute("name", `${name}`);
				meta.setAttribute("content", value);

				document.head.appendChild(meta);
			}
		},
	},
};
