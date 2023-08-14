const types = {
  control: {
    unload: 'removeControl'
  },
  layer: {
    unload: 'removeTileLayer'
  },
  overlay: {
    unload: 'removeOverlay'
  },
  contextMenu: {
    unload: 'removeContextMenu'
  }
}

const getParent = $component => ($component.abstract || $component.$el === $component.$children[0].$el) ? getParent($component.$parent) : $component

function destroyInstance () {
  const { unload, renderByParent, $parent } = this
  if (renderByParent) {
    $parent.reload()
  }
  unload()
}

class Mixin {
  constructor (prop) {
    this.methods = {
      ready () {
        const $parent = getParent(this.$parent)
        const TMap = this.TMap = $parent.TMap
        const map = this.map = $parent.map
        this.load()
        this.$emit('ready', {
          TMap,
          map
        })
      },
      transmitEvent (e) {
        this.$emit(e.type.replace(/^on/, ''), e)
      },
      reload () {
        this && this.TMap && this.$nextTick(() => {
          this.unload()
          this.$nextTick(this.load)
        })
      },
      unload () {
        const { map, originInstance } = this
        try {
          switch (prop.type) {
            case 'search':
              return originInstance.clearResults()
            case 'autoComplete':
            case 'lushu':
              return originInstance.dispose()
            case 'markerClusterer':
              return originInstance.clearMarkers()
            default:
              map[types[prop.type].unload](originInstance)
          }
        // eslint-disable-next-line no-empty
        } catch (e) {}
      }
    }
    this.computed = {
      renderByParent () {
        return this.$parent.preventChildrenRender
      }
    }
    this.mounted = function () {
      const $parent = getParent(this.$parent)
      const map = $parent.map
      const { ready } = this
      map ? ready() : $parent.$on('ready', ready)
    }
    this.destroyed = destroyInstance
    this.beforeDestroy = destroyInstance
  }
}

export default type => new Mixin({ type })
