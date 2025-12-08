<template>
	<div class="image" :class="{ 'image--clear': !path }" ref="image">
		<template v-if="!path">
			<img :src="default" ref="imageImg" :alt="alt" />
		</template>

		<template v-else>
			<img :src="path" ref="imageImg" :alt="alt" />
		</template>
	</div>
</template>
<script>
export default {
	props: {
		path: {
			type: String,
			required: "",
		},
		default: {
			type: String,
			required: "",
		},
		alt: {
			type: String,
			required: "Картинка",
		},
		height: {
			type: [Number, String],
			required: "",
		},
	},
	mounted() {
		const rootStyles = getComputedStyle(document.documentElement);
		const myVariable = rootStyles.getPropertyValue("--image-height").trim();

		if (this.height) {
			switch (typeof this.height) {
				case "string":
					this.$refs.image.style.height = this.height;
					break;
				case "number":
					this.$refs.image.style.height = this.height + "px";
					break;
			}
		} else {
			this.$refs.image.style.height = myVariable;
		}
	},
};
</script>

<style scoped>
.image {
	position: relative;
	padding: var(--image-padding);
	border: var(--image-border);
	border-radius: var(--image-border-radius);

	height: var(--image-height);
	min-height: var(--image-height);

	background-color: var(--image-background-color);
}

.image img {
	user-select: none;
	border: var(--image-img-border);
	border-radius: var(--image-img-border-radius);

	object-fit: var(--image-object-fit);
	width: var(--image-img-width);
	height: var(--image-img-height);

	animation: var(--image-img-animation);

	background-color: var(--image-img-background-color);
}

.image--clear img {
	background-color: var(--image-img-background-color--clear);
}
</style>
