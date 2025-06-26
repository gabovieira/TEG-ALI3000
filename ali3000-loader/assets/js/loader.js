class Ali3000Loader {
  constructor() {
    this.progress = 0
    this.isComplete = false
    this.progressFill = document.getElementById("progressFill")
    this.progressPercent = document.getElementById("progressPercent")
    this.logoWrapper = document.getElementById("logoWrapper")
    this.loadingDots = document.getElementById("loadingDots")
    this.successMessage = document.getElementById("successMessage")

    this.init()
  }

  init() {
    this.startLoading()
  }

  startLoading() {
    const interval = setInterval(() => {
      if (this.progress >= 100) {
        this.complete()
        clearInterval(interval)
        return
      }

      // Incremento aleatorio para simular carga real
      this.progress += Math.random() * 15
      this.progress = Math.min(this.progress, 100)

      this.updateProgress()
    }, 200)
  }

  updateProgress() {
    const roundedProgress = Math.round(this.progress)
    this.progressFill.style.width = `${roundedProgress}%`
    this.progressPercent.textContent = `${roundedProgress}%`
  }

  complete() {
    this.isComplete = true
    this.progress = 100
    this.updateProgress()

    // Animaciones de completado
    this.logoWrapper.classList.add("complete")
    this.loadingDots.style.display = "none"
    this.successMessage.style.display = "flex"

    // Opcional: redirigir después de un tiempo
    setTimeout(() => {
      this.onComplete()
    }, 2000)
  }

  onComplete() {
    // Aquí puedes agregar la lógica para redirigir o continuar
    // Por ejemplo:
    // window.location.href = 'dashboard.php';
    console.log("Carga completada - Listo para continuar")
  }
}

// Inicializar el loader cuando el DOM esté listo
document.addEventListener("DOMContentLoaded", () => {
  new Ali3000Loader()
})
