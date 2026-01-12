<template>
	<!-- Кнопки -->
	<div class="main__buttons">
		<button class="main__buttons-button" @click="topFunction">
			<VueIcon :name="'Arrow'" :fill="'white'" :width="'26px'" :height="'26px'" />
		</button>
	</div>
</template>

<script>
import api from "../../../../mixin/api";
import validate from "../../../../services/validate";
import shared from "../../../../services/shared";

export default {
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

		/* |‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾‾|*/
		/* |                     ЗАТЫЧКИ                       |*/
		/* |___________________________________________________|*/
		sayHelloPoints() {
			switch (true) {
				case this.countPoints < 3:
					this.$store.commit("addDebugger", {
						title: "Упс...",
						body: "Это ещё не готово, но уже скоро будет :)",
						type: "completed",
					});

					this.countPoints++;
					break;

				case this.countPoints >= 3 && this.countPoints < 6:
					this.$store.commit("addDebugger", {
						title: "Ой!",
						body: "Всё-таки, это ещё не готово, но уже скоро будет :)",
						type: "completed",
					});

					this.countPoints++;
					break;

				case this.countPoints == 6:
					this.$store.commit("addDebugger", {
						title: "Ошибка.",
						body: "Вы сломали матрицу...",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints == 7:
					this.$store.commit("addDebugger", {
						title: "ФЫык0_-йц0",
						body: "С#!@%^&* ()_+~",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints == 8:
					this.$store.commit("addDebugger", {
						title: "КритичЕсК0й сб0й...",
						body: "╬╠╬╠╣╠╬",
						type: "error",
					});

					this.countPoints++;
					break;

				case this.countPoints >= 9:
					this.$store.commit("addDebugger", {
						title: "Шутка!",
						body: "Вы ничего не сломали! Но всё-таки, ещё не готово и вам следует подождать :)",
						type: "completed",
					});

					this.countPoints = 0;
					break;
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
	z-index: 5;

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
	box-shadow: var(--default-box-shadow);
}

.main__buttons-button.rotate {
	transform: rotate(-90deg);
}

.main__buttons-button:hover {
	background-color: var(--primary-color-hover);
}

/* Баллы */
.modal__result {
	display: grid;
	grid-template-columns: repeat(2, 1fr);
	gap: var(--default-gap);

	margin-top: 10px;

	border-radius: calc(var(--default-border-radius) / 1.5);
}

.modal__result-item {
	box-sizing: border-box;
	display: flex;
	flex-direction: column;
	justify-content: space-between;

	border-radius: calc(var(--default-border-radius) / 2);
	padding: var(--default-padding);

	min-height: 200px;
	background-color: var(--skeleton-background-color);
}

.result__item-header {
	display: flex;
	justify-content: space-between;

	font-size: 1.25rem;
	color: black;
}

.result__item-barcode {
	display: flex;
	flex-direction: column;
	justify-content: center;
	align-items: center;

	border-radius: calc(var(--default-border-radius) / 2);
	background-color: white;
	min-height: 100px;
}

@media screen and (width <= 800px) {
	.modal__result {
		grid-template-columns: repeat(1, 1fr);
	}
}

@media screen and (width <= 850px) {
	.main__buttons {
		bottom: 20px;
		right: 20px;
	}
}
</style>
