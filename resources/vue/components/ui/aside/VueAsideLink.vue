<template>
	<a
		:href="link"
		class="aside__link"
		:class="{ 
			'aside__link--child': isChild, 
			'aside__link--active': isActive, 
			'aside__link--small': isSmall, 
		}"
	>
		<div class="aside__link-stage" v-if="isChild && !$store.getters.getAsideSmall">
			<div class="aside__link-stage-point"></div>
		</div>

		<slot></slot>
	</a>
</template>

<script>
export default {
	props: {
		link: {
			type: String,
			default: "#",
		},
		isChild: {
			type: Boolean,
			default: false,
		},
		isActive: {
			type: Boolean,
			default: false,
		},
		isSmall: {
			type: Boolean,
			default: false,
		},
	},
};
</script>

<style>
a.aside__link {
	position: relative;
	cursor: pointer;

	box-sizing: border-box;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	gap: calc(var(--default-gap) / 2);

	white-space: nowrap; /* нет переноса */
	padding: var(--aside-link-padding);
	outline: none;

	width: var(--aside-link-width);
	height: var(--aside-link-height);
	min-height: var(--aside-link-height);
	font-size: var(--aside-link-font-size);

	border-radius: var(--aside-link-border-radius);
	border: var(--aside-link-border);

	text-decoration: none;
	color: var(--aside-link-font-color);
	background-color: var(--aside-link-background-color);
	outline: var(--aside-link-outline);

	transition: var(--aside-link-transition);
}

a.aside__link:is(:hover, .aside__link--active) {
	color: var(--aside-link-font-color-hover);

	border: var(--aside-link-border-hover);
	background-color: var(--aside-link-background-color-hover);
}

a.aside__link svg {
	fill: var(--aside-link-icon-fill);
	transition: all 0s;
}

a.aside__link:is(:hover, .aside__link--active) svg {
	fill: var(--aside-link-icon-fill-hover);
}

a.aside__link.aside__link--child {
	border: var(--aside-link-child-border);
	background-color: var(--aside-link-child-background-color);

	color: var(--aside-link-child-font-color);
}

a.aside__link.aside__link--child:is(:hover, .aside__link--active) {
	border: var(--aside-link-child-border-hover);
	background-color: var(--aside-link-child-background-color-hover);

	color: var(--aside-link-child-font-color-hover);
}

a.aside__link.aside__link--child svg {
	fill: var(--aside-link-child-svg-fill);
}

a.aside__link.aside__link--child:is(:hover, .aside__link--active) svg {
	fill: var(--aside-link-child-svg-fill-hover);
}

a.aside__link.aside__link--small {
	padding: var(--aside-link-small-padding);

	width: var(--aside-link-small-width);
	height: var(--aside-link-small-height);
}

.aside__link-stage {
	position: absolute;
	left: var(--aside-link-stage-left);
	top: var(--aside-link-stage-top);

	border-radius: var(--aside-link-stage-border-radius);

	height: var(--aside-link-stage-height);
	width: var(--aside-link-stage-width);

	background-color: var(--aside-link-stage-background-color);

	transition: all 0.1s;
}

.aside__link-stage-point {
	position: absolute;
	left: var(--aside-link-point-left);
	top: var(--aside-link-point-top);

	width: var(--aside-link-point-width);
	height: var(--aside-link-point-height);

	border-radius: var(--aside-link-point-border-radius);
	background-color: var(--aside-link-point-background-color);

	transition: var(--aside-link-point-transition);
}

a.aside__link.aside__link--child:is(:hover, .aside__link--active) .aside__link-stage {
	background-color: var(--aside-link-stage-hover-background-color);
}

a.aside__link.aside__link--child:is(:hover, .aside__link--active) .aside__link-stage .aside__link-stage-point {
	background-color: var(--aside-link-stage-hover-background-color);
}

@media screen and (width < 850px) {
	a.aside__link.aside__link--small {
		padding: var(--aside-link-padding);

		width: var(--aside-link-width);
		height: var(--aside-link-height);
	}
}
</style>
