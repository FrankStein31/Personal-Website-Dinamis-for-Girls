        <!-- Footer spacing -->
        <footer style="margin-top: 50px; text-align: center; color: var(--text-muted); font-size: 0.85rem; border-top: 1px solid var(--border); padding-top: 20px;">
            &copy; <?= date('Y') ?> Personal Website. All Rights Reserved.
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
