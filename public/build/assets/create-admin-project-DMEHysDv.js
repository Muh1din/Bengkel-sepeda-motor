document.addEventListener(`DOMContentLoaded`,function(){let e=document.getElementById(`add-gallery`),t=document.getElementById(`gallery-container`);e.addEventListener(`click`,function(){let e=document.createElement(`div`);e.className=`gallery-item flex items-center gap-3`,e.innerHTML=`
                <input
                    type="file"
                    name="gallery[]"
                    accept="image/jpeg,image/png,image/webp"
                    class="block w-full rounded-lg border border-ui-border text-sm
                           file:mr-4 file:border-0 file:bg-gray-100
                           file:px-4 file:py-2.5 file:font-medium
                           hover:file:bg-gray-200"
                >

                <button
                    type="button"
                    class="remove-gallery shrink-0 rounded-lg px-3 py-2 text-sm
                           font-medium text-red-600 transition hover:bg-red-50"
                >
                    Hapus
                </button>
            `,t.appendChild(e)}),t.addEventListener(`click`,function(e){let t=e.target.closest(`.remove-gallery`);t&&t.closest(`.gallery-item`).remove()})});