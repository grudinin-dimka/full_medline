export default {
	basename(path) {
		return path.split("/").reverse()[0];
	},

	basetype(filename) {
		return filename.split(".").reverse()[0];
	},
};
