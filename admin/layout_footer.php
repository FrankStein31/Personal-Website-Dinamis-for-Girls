<!-- Footer spacing -->
        <footer style="margin-top: 50px; text-align: center; color: var(--text-muted); font-size: 0.85rem; border-top: 1px solid var(--border); padding-top: 20px;">
            <div style="margin-top: 30px; border-radius: var(--radius-md); overflow: hidden; border: 1px solid var(--border); background: var(--card-bg); text-align: left;">
                <div style="padding: 20px 28px; display: flex; align-items: center; justify-content: space-between; gap: 16px; flex-wrap: wrap;">
                    <div style="display: flex; align-items: center; gap: 12px;">
                        <div style="width: 40px; height: 40px; border-radius: 50%; background: rgba(var(--primary-rgb), 0.1); display: flex; align-items: center; justify-content: center;">
                            <i class="fa-solid fa-code" style="color: var(--primary); font-size: 16px;"></i>
                        </div>
                        <div>
                            <p style="margin: 0; font-size: 13px; color: var(--text-muted);">Built &amp; designed by</p>
                            <p style="margin: 0; font-size: 15px; font-weight: 600; color: var(--text);">Frankie Steinlie &amp; Steinlie Joki</p>
                        </div>
                    </div>
                    <div style="display: flex; align-items: center; gap: 8px; flex-wrap: wrap;">
                        <a href="https://frankie-steinlie.page.gd/" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: rgba(var(--primary-rgb), 0.1); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; color: var(--primary); text-decoration: none;">
                            <i class="fa-solid fa-user-circle" style="font-size: 14px;"></i>
                            frankie-steinlie
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                        </a>
                        <a href="https://steinliejoki.page.gd/" target="_blank" style="display: inline-flex; align-items: center; gap: 6px; padding: 7px 14px; background: var(--bg-secondary); border-radius: var(--radius-sm); font-size: 13px; font-weight: 600; color: var(--text); text-decoration: none; border: 1px solid var(--border);">
                            <i class="fa-solid fa-user-circle" style="font-size: 14px;"></i>
                            steinliejoki
                            <i class="fa-solid fa-arrow-up-right-from-square" style="font-size: 11px;"></i>
                        </a>
                    </div>
                </div>

                <div style="height: 1px; background: var(--border);"></div>

                <div style="padding: 10px 28px; display: flex; align-items: center; gap: 6px; background: var(--bg-secondary);">
                    <i class="fa-solid fa-heart" style="font-size: 12px; color: #e24b4a; animation: pulse 2s infinite;"></i>
                    <span style="font-size: 12px; color: var(--text-muted);">Crafted with care — Frankie Steinlie © 2026. All rights reserved.</span>
                </div>
            </div>
        </footer>
    </main>

    <!-- Global Modal Structure for Previews in Admin (Optional, but nice to have) -->
    <div id="adminModal" class="modal-backdrop" onclick="closeAdminModal(event)">
        <div class="modal-content" onclick="event.stopPropagation()">
            <button class="modal-close" onclick="hideAdminModal()">&times;</button>
            <div id="adminModalBody"></div>
        </div>
    </div>

    <script>
        function showAdminModal(contentHtml) {
            const modal = document.getElementById('adminModal');
            const body = document.getElementById('adminModalBody');
            body.innerHTML = contentHtml;
            modal.classList.add('show');
        }

        function hideAdminModal() {
            const modal = document.getElementById('adminModal');
            modal.classList.remove('show');
            setTimeout(() => {
                document.getElementById('adminModalBody').innerHTML = '';
            }, 300);
        }

        function closeAdminModal(e) {
            if (e.target.id === 'adminModal') {
                hideAdminModal();
            }
        }
    </script>
</body>
</html>