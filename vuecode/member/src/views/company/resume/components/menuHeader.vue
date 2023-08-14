<template>
	<div class="browsing_wrapper">
		<p><slot></slot></p>
	</div>
</template>

<script>
</script>

<style lang="scss" scoped>
	.browsing_wrapper{
		display: flex;
		line-height: 40px;
		margin-bottom: 27px;
	}
	.browsing_wrapper>p{
		flex: 1;
		font-size: 17px;
		color: #333333;
		font-weight: bold;
	}
</style>
