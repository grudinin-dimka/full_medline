<template>
	<div
		class="specialist"
		v-for="specialist in specialists"
		:key="specialist.id"
	>
		<img :src="specialist.path" />
		<div class="specialist-body">
			<div class="specialist-body-options">
				<div class="specialist-body-options-title">
					{{ specialist.family + " " + specialist.name + " " + specialist.surname }}
				</div>
				<div class="specialist-body-options-other">
					<div class="specialist-body-options-other-block">
						<div>Специализация:</div>
						<div>{{ specialist.specialization }}</div>
					</div>
					<div class="specialist-body-options-other-block">
						<div>Стаж:</div>
						<div>{{ specialist.startWorkAge }}</div>
					</div>
					<div class="specialist-body-options-other-block">
						<div>Ссылка:</div>
						<div>
							{{ specialist.link }}
						</div>
					</div>
				</div>
			</div>
			<div
				class="specialist-body-link"
				@click="openspecialistProfile(specialist)"
			>
				Подробнее
			</div>
		</div>
	</div>
</template>

<script>
export default {
	components: {},
	props: {
		specialists: {
			type: Array,
			default: () => [],
			Required: true,
		},
	},
	methods: {
		openspecialistProfile(specialist) {
			window.scrollTo({
				top: 0,
				left: 0,
				behavior: "smooth",
			});
			this.$router.push("/specialists/" + specialist.url);
		},
	},
};
</script>

<style scoped>
.list-enter-active,
.list-leave-active {
	transition: all 0.5s ease;
}
.list-enter-from,
.list-leave-to {
	opacity: 0;
	transform: translateX(30px);
}

.specialist {
	box-sizing: border-box;
	display: flex;
	justify-content: center;
	gap: 20px;

	border: 2px solid var(--input-border-color-inactive);
	border-radius: 20px;
	padding: 20px;

	width: 1000px;

	animation: show 0.5s ease-out;
	transition: all 0.2s;
}

.specialist > img {
	width: 230px;
	border-radius: 15px;
	background-size: contain;
	background-position: center center;
	background-repeat: no-repeat;
	height: 100%;
}

.specialist-body {
	flex: 1 0 0px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
}

.specialist-body-options {
	display: flex;
	flex-direction: column;
	gap: 7px;
}

.specialist-body-options-title {
	font-size: 24px;
}

.specialist-body-options-other {
	display: flex;
	flex-direction: column;
	gap: 10px;
}

.specialist-body-options-other-block {
	display: grid;
	grid-template-columns: 150px 1fr;
}

.specialist-body-options-other-block > div {
	font-size: 16px;
}

.specialist-body-price {
	font-size: 20px;
	text-align: right;
}

.specialist-body-advanced {
	display: flex;
	justify-content: space-between;
}

.specialist-body-link {
	margin-top: 10px;
	font-size: 16px;
	color: var(--primary-color);
	cursor: pointer;
	text-align: right;
}

@keyframes show {
	0% {
		opacity: 0;
		transform: translateY(30px);
	}
	100% {
		opacity: 1;
		transform: translateY(0);
	}
}

@media screen and (max-width: 1080px) {
	.specialist {
		width: 100%;
	}
}

@media screen and (max-width: 775px) {
	.specialist {
		flex-direction: column;
		justify-content: center;
		align-items: center;
	}
}

@media screen and (max-width: 440px) {
	.specialist-body-options-other {
		display: none;
	}

	.specialist > img {
		width: 100%;
		max-width: 230px;
	}
}
</style>
