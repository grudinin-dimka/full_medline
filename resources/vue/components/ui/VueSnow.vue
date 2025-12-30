<template>
  <canvas ref="canvas" class="snow-canvas"></canvas>
</template>

<script>
export default {
  name: 'Snow',
  props: {
    density: {
      type: Number,
      default: 150
    },
    speed: {
      type: Number,
      default: 1
    },
    size: {
      type: Number,
      default: Number
    },
    color: {
      type: String,
      default: '255,255,255'
    },
    opacity: {
      type: Number,
      default: 0.8
    }
  },
  data() {
    return {
      ctx: null,
      flakes: [],
      animationId: null
    }
  },
  mounted() {
    this.ctx = this.$refs.canvas.getContext('2d')
    this.resize()
    this.createFlakes()
    this.draw()
    window.addEventListener('resize', this.resize)
  },
  beforeUnmount() {
    cancelAnimationFrame(this.animationId)
    window.removeEventListener('resize', this.resize)
  },
  methods: {
    resize() {
      const canvas = this.$refs.canvas
      canvas.width = window.innerWidth
      canvas.height = window.innerHeight
    },
    createFlakes() {
      const canvas = this.$refs.canvas
      this.flakes = Array.from({ length: this.density }).map(() => ({
        x: Math.random() * canvas.width,
        y: Math.random() * canvas.height,
        r: this.size
          ? Math.random() * this.size + 1
          : Math.random() * 3 + 1,
        d: Math.random() * this.speed + 0.5
      }))
    },
    draw() {
      const canvas = this.$refs.canvas
      this.ctx.clearRect(0, 0, canvas.width, canvas.height)
      this.ctx.fillStyle = `rgba(${this.color},${this.opacity})`
      this.ctx.beginPath()

      this.flakes.forEach(f => {
        this.ctx.moveTo(f.x, f.y)
        this.ctx.arc(f.x, f.y, f.r, 0, Math.PI * 2)
      })

      this.ctx.fill()
      this.update()
    },
    update() {
      const canvas = this.$refs.canvas

      this.flakes.forEach(f => {
        f.y += f.d
        if (f.y > canvas.height) {
          f.y = -5
          f.x = Math.random() * canvas.width
        }
      })

      this.animationId = requestAnimationFrame(this.draw)
    }
  },
  watch: {
    density() {
      this.createFlakes()
    },
    speed() {
      this.createFlakes()
    },
    size() {
      this.createFlakes()
    }
  }
}
</script>

<style scoped>
.snow-canvas {
  position: fixed;
  inset: 0;
  pointer-events: none;
  z-index: 9999;
}
</style>
