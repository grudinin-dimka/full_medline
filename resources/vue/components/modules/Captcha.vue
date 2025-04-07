<template>
	<div class="captcha">
		<div class="content">
			<div class="text">
				{{ captcha }}
				<div class="line" ref="line"></div>
			</div>
			<div class="update" @click="reloadCaptcha">
				<svg
					xmlns="http://www.w3.org/2000/svg"
					height="26px"
					viewBox="0 -960 960 960"
					width="26px"
				>
					<path
						d="M480-160q-134 0-227-93t-93-227q0-134 93-227t227-93q69 0 132 28.5T720-690v-110h80v280H520v-80h168q-32-56-87.5-88T480-720q-100 0-170 70t-70 170q0 100 70 170t170 70q77 0 139-44t87-116h84q-28 106-114 173t-196 67Z"
					/>
				</svg>
			</div>
		</div>
	</div>
</template>

<script>
export default {
	data() {
		return {
			captcha: null,
			rotate: 0,
		};
	},
	methods: {
		reloadCaptcha() {
			this.generateCaptcha();
			this.generateRotate();
		},
		generateCaptcha() {
			// Создание алгоритма генерации каптчи из символов 0-9, a-z, A-Z
			let chars = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			let captcha = "";
			for (let i = 0; i < 9; i++) {
				captcha += chars.charAt(Math.floor(Math.random() * chars.length));
			}

			this.captcha = captcha;
		},
		generateRotate() {
			let chance = Math.floor(Math.random() * 10);
			if (chance >= 5) {
				this.rotate = Math.floor(Math.random() * 25);
			} else {
				this.rotate = Math.floor(Math.random() * -25);
			}
			this.$refs.line.style.transform = `rotate(${this.rotate}deg)`;
		},
	},
	mounted() {
		this.generateCaptcha();
		this.generateRotate();
	},
};
</script>

<style scoped>
.captcha {
	display: flex;
	justify-content: center;
	align-items: center;
}

.captcha > .content {
	position: relative;
}

.captcha > .content > .text {
	overflow: hidden;
	display: flex;
	justify-content: center;
	align-items: center;
	position: relative;
	user-select: none;

	border-radius: 10px;
	background-color: rgb(245, 245, 245);

	width: 250px;
	height: 50px;

	font-family: "Azeret Mono";
	font-style: italic;
   font-weight: 100;
	font-size: 22px;
}

.captcha > .content > .text > .line {
	position: absolute;
	width: 130px;
	height: 1px;

	background-color: rgb(0, 0, 0);
}

.captcha > .content > .update {
	position: absolute;
	cursor: pointer;

	top: 10px;
	right: 5px;
}

.captcha > .content > .update > svg {
	fill: rgb(201, 201, 201);
}
</style>
