export default {
  createLoadScreen (targetElementId) {
    const screen                 = document.createElement('div')
    screen.id                    = 'load-screen'
    screen.style.backgroundColor = 'rgb(33, 33, 33)'
    screen.style.opacity         = 0.46
    screen.style.width           = '100%'
    screen.style.height          = '100%'
    screen.style.position        = 'absolute'
    screen.style.top             = 0
    screen.style.bottom          = 0
    screen.style.right           = 0
    screen.style.left            = 0

    const loader          = document.createElement('div')
    loader.style.position = 'absolute'
    loader.style.bottom   = '2%'
    loader.style.right    = '2%'
    loader.innerHTML      = '...読み込み中...'

    screen.insertBefore(loader, screen.firstChild)

    const targetElement = document.getElementById(`${targetElementId}`)
    targetElement.before(screen)
  },
  deleteLoadScreen () {
    const screen = document.getElementById('load-screen')
    screen.remove()
  },
}
