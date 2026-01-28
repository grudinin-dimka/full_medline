<template>
	<div class="accordeon" ref="accordeon">
		<div class="accordeon__head" ref="accordeonHead">
			<div class="accordeon__head-name" v-if="$slots.name">
				<slot name="name"></slot>
			</div>
			<div class="accordeon__head-name" v-else>Меню</div>

			<div class="accordeon__head-buttons">
				<slot name="buttons"></slot>

				<VueAccordeonButton @click.prevent="changeAccordeon">
					<svg ref="accordeonButton" xmlns="http://www.w3.org/2000/svg" width="16" height="8" viewBox="0 0 10 5">
						<path d="M5 5L0 0H10L5 5Z" />
					</svg>
				</VueAccordeonButton>
			</div>
		</div>
		<div class="accordeon__body" ref="accordeonContent">
			<slot name="body"></slot>
		</div>
	</div>
</template>

<script>
import VueAccordeonButton from "./VueAccordeonButton.vue";

export default {
	props: {
		padding: {
			type: Number,
			default: 20,
		},
		gap: {
			type: Number,
			default: 20,
		},
	},
	components: { 
		VueAccordeonButton 
	},
	data() {
		return {
			type: "Открыть",
		};
	},
	methods: {
		changeAccordeon() {
			let accordeon = this.$refs.accordeon;
			let accordeonHead = this.$refs.accordeonHead;
			let accordeonContent = this.$refs.accordeonContent;

			let elements = accordeonContent.children;

			let height = elements.length * this.gap + this.padding;

			for (let i = 0; i < elements.length; i++) {
				height += elements[i].offsetHeight;
			}

			if (accordeonContent.style.maxHeight && accordeonContent.style.maxHeight != "0px") {
				this.$refs.accordeonHead.classList.remove("active");
				this.$refs.accordeonButton.style.transform = "rotate(0deg)";
				accordeonContent.style.maxHeight = "0px";

				this.type = "Открыть";
				return;
			}

			this.$refs.accordeonHead.classList.add("active");
			this.$refs.accordeonButton.style.transform = "rotate(180deg)";
			accordeonContent.style.maxHeight = height + "px";
			this.type = "Закрыть";
		},
	},
};
</script>

<style scoped>
.accordeon {
	position: relative;
	display: flex;
	flex-direction: column;

	border: var(--accordeon-border);
	border-radius: var(--accordeon-border-radius);
	padding: var(--accordeon-padding);
	margin: var(--accordeon-margin);

	background-color: var(--accordeon-background-color);
}

.accordeon__head {
	display: grid;
	/* justify-content: space-between; */
	grid-template-columns: 1fr auto;
	align-items: center;
	gap: var(--accordeon-head-gap);

	padding: var(--accordeon-head-padding);
	border: var(--accordeon-head-border);
	border-radius: var(--accordeon-head-border-radius);
}

.accordeon__head-name {
	user-select: none;
	display: flex;
	align-items: center;
	flex: 1 0 150px;

	padding: var(--accordeon-head-name-padding);
	border-radius: var(--accordeon-head-name-border-radius);
	border: var(--accordeon-head-name-border);

	min-height: 62px;
	font-size: 1.125rem;
	color: black;

	background-color: var(--accordeon-head-name-background-color);

	transition: all 0.2s;
}

.accordeon__head-buttons {
	display: flex;
	gap: var(--accordeon-head-gap);
}

.accordeon__body {
	overflow: hidden;
	max-height: 0px;

	display: flex;
	flex-direction: column;
	transition: max-height 0.35s ease;
}
</style>
