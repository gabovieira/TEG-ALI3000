<!-- Loader minimalista centrado con logo, texto y spinner -->
<style>
.loader-overlay {
  position: fixed;
  inset: 0;
  background: #f8f9fa;
  z-index: 9999;
  display: flex;
  align-items: center;
  justify-content: center;
}
.loader-box {
  display: flex;
  flex-direction: column;
  align-items: center;
  gap: 1.5rem;
}
.loader-logo {
  width: 90px;
  height: 90px;
  border-radius: 50%;
  background: #fff;
  display: flex;
  align-items: center;
  justify-content: center;
  box-shadow: 0 2px 16px 0 #0001;
  position: relative;
}
.loader-spinner {
  position: absolute;
  top: -10px; left: -10px;
  width: 110px; height: 110px;
  border-radius: 50%;
  border: 4px solid #e5e7eb;
  border-top: 4px solid #2563eb;
  animation: spin 1s linear infinite;
}
@keyframes spin { 100% { transform: rotate(360deg); } }
.loader-title {
  font-size: 2rem;
  font-weight: bold;
  color: #222;
  letter-spacing: 1px;
  text-align: center;
}
.loader-subtitle {
  font-size: 1rem;
  color: #b91c1c;
  letter-spacing: 0.2em;
  text-align: center;
  font-weight: 600;
}
</style>
<div id="loader" class="loader-overlay">
  <div class="loader-box">
    <div class="loader-logo">
      <div class="loader-spinner"></div>
      <img src="/ali3000/assets/img/logoali3000.png" alt="ali3000 consultores logo" style="width:60px; height:60px; object-fit:contain; z-index:1;" />
    </div>
    <div class="loader-title">ali3000</div>
    <div class="loader-subtitle">CONSULTORES</div>
  </div>
</div>
