<template>
	<div class="captcha">
		<canvas ref="canvas" :width="width" :height="height"></canvas>

		<button class="captcha__refresh" ref="refresh">
			<VueIcon
				class="captcha__refresh-icon"
				:name="'Refresh'"
				:width="'28px'"
				:height="'28px'"
				@click="generateCaptcha()"
			/>
		</button>
	</div>
</template>

<script>
export default {
	props: {
		modelValue: {
			type: String,
			default: "",
		},
		// Ширина
		width: {
			type: Number,
			default: 200,
		},
		// Высота
		height: {
			type: Number,
			default: 50,
		},
		// Количество точек
		dots: {
			type: Number,
			default: 300,
		},
		// Количество линий
		lines: {
			type: Number,
			default: 5,
		},
		// Длина текста
		length: {
			type: Number,
			default: 5,
		},
		distortion: {
			type: Number,
			default: 0,
		},
		// Шрифт
		font: {
			type: Object,
			default: {
				min: 26,
				max: 30,
				baseline: "middle",
			},
		},
		// Символы
		chars: {
			type: String,
			default: "ABCDEFGHJKLMNPQRSTUVWXYZabcdefghjkmnpqrstuvwxyz23456789",
		},
	},
	data() {
		return {
			ctx: null,
		};
	},
	watch: {
		modelValue(newValue, oldValue) {
			if (newValue === "") this.generateCaptcha();
		},
	},
	methods: {
		random(min, max) {
			return Math.random() * (max - min) + min;
		},

		randomColor(min = 0, max = 255) {
			const r = Math.floor(this.random(min, max));
			const g = Math.floor(this.random(min, max));
			const b = Math.floor(this.random(min, max));
			return `rgb(${r},${g},${b})`;
		},

		generateText() {
			let text = "";
			for (let i = 0; i < this.length; i++) {
				text += this.chars[Math.floor(Math.random() * this.chars.length)];
			}
			return text;
		},

		drawNoise() {
			// точки
			for (let i = 0; i < this.dots; i++) {
				this.ctx.fillStyle = this.randomColor(150, 255);
				this.ctx.beginPath();
				this.ctx.arc(this.random(0, this.width), this.random(0, this.height), this.random(0.5, 1.0), 0, Math.PI * 2);
				this.ctx.fill();
			}

			// линии
			for (let i = 0; i < this.lines; i++) {
				this.ctx.strokeStyle = this.randomColor(100, 200);
				this.ctx.beginPath();
				this.ctx.moveTo(this.random(0, this.width), this.random(0, this.height));
				this.ctx.lineTo(this.random(0, this.width), this.random(0, this.height));
				this.ctx.stroke();
			}
		},

		drawText(text) {
			const charSpace = this.width / (text.length + 1);

			for (let i = 0; i < text.length; i++) {
				const char = text[i];

				const x = charSpace * (i + 1) + this.random(-10, 10);
				const y = this.height / 2 + this.random(-10, 10);
				const angle = this.random(-0.5, 0.5);

				this.ctx.save();
				this.ctx.translate(x, y);
				this.ctx.rotate(angle);

				this.ctx.font = `${this.random(this.font.min ?? 26, this.font.max ?? 30)}px sans-serif`;
				this.ctx.fillStyle = this.randomColor(20, 120);
				this.ctx.textBaseline = this.font.baseline ?? "middle";
				this.ctx.fillText(char, 0, 0);

				this.ctx.restore();
			}
		},

		drawDistortedText(text) {
			const tempCanvas = document.createElement("canvas");
			tempCanvas.width = this.width;
			tempCanvas.height = this.height;

			const tctx = tempCanvas.getContext("2d");

			// рисуем текст как обычно
			const charSpace = this.width / (text.length + 1);

			for (let i = 0; i < text.length; i++) {
				const char = text[i];

				const x = charSpace * (i + 1) + this.random(-10, 10);
				const y = this.height / 2 + this.random(-10, 10);
				const angle = this.random(-0.5, 0.5);

				tctx.save();
				tctx.translate(x, y);
				tctx.rotate(angle);

				tctx.font = `${this.random(this.font.min ?? 26, this.font.max ?? 30)}px sans-serif`;
				tctx.fillStyle = this.randomColor(20, 120);
				tctx.textBaseline = this.font.baseline ?? "middle";
				tctx.fillText(char, 0, 0);

				tctx.restore();
			}

			const amplitude = this.random(5, 15);
			const frequency = this.random(0.05, 0.1);

			for (let y = 0; y < this.height; y++) {
				const offsetX = Math.sin(y * frequency) * amplitude;

				this.ctx.drawImage(tempCanvas, 0, y, this.width, 1, offsetX, y, this.width, 1);
			}
		},

		generateCaptcha() {
			const text = this.generateText();

			this.ctx.clearRect(0, 0, this.width, this.height);

			// фон
			this.ctx.fillStyle = this.randomColor(220, 255);
			this.ctx.fillRect(0, 0, this.width, this.height);

			this.drawDistortedText(text);
			this.drawNoise();

			// тут можно эмитить текст наружу
			this.$emit("update:modelValue", text);
		},
	},
	mounted() {
		this.ctx = this.$refs.canvas.getContext("2d");
		this.generateCaptcha();

		this.$refs.refresh.style.width = `${this.height / 1.25}px`;
		this.$refs.refresh.style.height = `${this.height}px`;
	},
};
</script>

<style scoped>
.captcha {
	display: flex;
	position: relative;

	width: fit-content;
	margin: auto;

	border: var(--default-border);
	border-radius: var(--input-border-radius);
}

canvas {
	border-radius: var(--input-border-radius) 0px 0px var(--input-border-radius);
}

.captcha__refresh {
	border-radius: 0px var(--input-border-radius) var(--input-border-radius) 0px;

	border-top: 0px;
	border-right: 0px;
	border-bottom: 0px;
	border-left: var(--default-border-width);
	border-style: var(--default-border-style);
	border-color: var(--default-border-color);

	background-color: var(--input-background-color);
}

.captcha__refresh-icon {
	fill: black;
}

.captcha__refresh-icon:hover {
	fill: var(--primary-color);
}
</style>
