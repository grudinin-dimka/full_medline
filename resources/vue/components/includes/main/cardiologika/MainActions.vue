<template>
   <!-- Кнопки -->
	<div class="main__buttons">
		<button class="main__buttons-button" @click="topFunction">
			<VueIcon :name="'Arrow'" :fill="'white'" :width="'26px'" :height="'26px'" />
		</button>
	</div>
</template>

<script>
import VueIcon from "../../../modules/icon/VueIcon.vue";
import VueButton from "../../../ui/VueButton.vue";

export default {
	components: {
		VueButton,
		VueIcon,
	},
	methods: {
		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾| */
		/* |            Кнопки для пользователей               | */
		/* |___________________________________________________| */
		topFunction() {
			window.scrollTo({ top: 0, behavior: "smooth" });
		},

		scrollFunction() {
			var backToTop = document.getElementById("back-to-top");

			if (document.body.scrollTop > 20 || document.documentElement.scrollTop > 20) {
				backToTop.style.opacity = "1";
				backToTop.style.visibility = "visible";
			} else {
				backToTop.style.opacity = "0";
				backToTop.style.visibility = "hidden";
			}
		},
	},
	mounted() {
		window.addEventListener("scroll", this.setShadow);
	},
};
</script>

<style scoped>
/* Кнопки */
.main__buttons {
	position: fixed;
	bottom: 30px;
	right: 30px;
	z-index: 1;

	display: flex;
	flex-direction: column;
	gap: 10px;
}

.main__buttons-button {
	cursor: pointer;

	display: flex;
	justify-content: center;
	align-items: center;
	border: none;
	border-radius: 50%;
	outline: none;

	width: 70px;
	height: 70px;
	background-color: var(--primary-color);
	color: white;
	font-size: 16px;

	transition: all 0.2s ease-in-out;
	box-shadow: var(--default-shadow);
}

.main__buttons-button.rotate {
	transform: rotate(-90deg);
}

.main__buttons-button:hover {
	background-color: var(--primary-color-hover);
}

@media screen and (max-width: 750px) {
	.main__buttons {
		bottom: 20px;
		right: 20px;
	}
}
</style>
